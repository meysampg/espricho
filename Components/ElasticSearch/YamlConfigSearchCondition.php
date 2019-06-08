<?php

namespace Espricho\Components\ElasticSearch;

use Espricho\Components\Helpers\Arr;
use Espricho\Components\Contracts\SearchConditionInterface;
use Espricho\Components\ElasticSearch\Exceptions\YamlConfigQueryNotFoundException;

use function trim;
use function strpos;
use function substr;
use function sprintf;
use function in_array;
use function is_array;
use function array_map;
use function is_string;
use function array_merge;
use function json_encode;
use function array_values;
use function method_exists;

/**
 * Class YamlConfigSearchCondition gets query from yaml configurations of elastic
 * search under the queries key. This class is a for the time being solution and
 * it's better to develop https://github.com/meysampg/eql :|
 *
 * @package Espricho\Components\ElasticSearch
 */
class YamlConfigSearchCondition implements SearchConditionInterface
{
    /**
     * ElasticSearch Keywords
     */
    private const ELASTIC_KEYWORDS = [
         'match',
         'must',
         'should',
         'filter',
         'query',
         'range',
    ];

    /**
     * Query of the given key
     *
     * @var array
     */
    private $query = [];

    /**
     * Store information received from the request
     *
     * @var array
     */
    private $data = [];

    /**
     * The index of this searchable class
     *
     * @var string
     */
    private $index;

    /**
     * YamlConfigSearchCondition constructor.
     *
     * @param string $index
     * @param array  $data
     *
     * @throws YamlConfigQueryNotFoundException
     */
    public function __construct(string $index, array $data)
    {
        $this->index = $index;

        $index = sprintf('elasticsearch.queries.%s', trim($index));
        $query = sys()->getConfig($index, false);
        if (!$query) {
            throw new YamlConfigQueryNotFoundException(sprintf('Query %s not found.', $index));
        }

        $this->query = $query;
        $this->data  = $data;
    }

    /**
     * Get the index of the searchable instance
     *
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return YamlConfigSearchCondition
     */
    public function setData(array $data): YamlConfigSearchCondition
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build(): array
    {
        $data  = $this->populatePlaceHoldersWithData($this->getQueryData(), $this->getData());
        $query = $this->createSection('query', $data);

        return $query;
    }

    /**
     * Return JSON representation of the query
     *
     * @return string
     */
    public function buildRawJson(): string
    {
        return json_encode($this->build());
    }

    /**
     * Create a search term section
     *
     * @param string $name
     * @param array  $data
     * @param bool   $makeNested
     *
     * @return array
     */
    private function createSection(string $name, array $data, $makeNested = true): array
    {
        $query = [];

        if (method_exists($this, $name)) {
            return $this->{$name}($data, '', $makeNested);
        }

        foreach ($data as $term => $value) {
            if ($this->isESKeyword($term)) {
                [$method, $scope] = $this->getESMethodAndScope($term);

                if (method_exists($this, $method)) {
                    $value = $this->{$method}($value, $name, $makeNested);
                } elseif (is_array($value)) {
                    $value = $this->createSection($method, $value, $makeNested);
                }

                if ($scope) {
                    if (isset($query[$scope])) {
                        $query[$scope] = array_merge($query[$scope], $value);
                    } else {
                        $query[$scope] = $value;
                    }
                } else {
                    $query = array_merge($query, (array)$value);
                }
            } else {
                if ($this->isNested($term) && $makeNested) {
                    [$name, $scope] = $this->getESMethodAndScope($name);
                    $scope = $scope ?? '';
                    $value = $this->makeNested($term, $name, $scope, $value, false);
                }

                $query[$term] = $value;
            }
        }

        if (empty($query)) {
            return [];
        }

        return [$name => $query];
    }

    /**
     * Create a `match` term
     *
     * @param array  $query
     * @param string $from
     * @param bool   $makeNested
     *
     * @return array
     */
    private function match(array $query, string $from = 'must', $makeNested = true): array
    {
        $newQuery = [];

        foreach ($query as $term => $value) {
            if ($this->isNested($term) && $makeNested) {
                $result     = $this->makeNested($term, 'match', $from, $value);
                $newQuery[] = $result;
            } else {
                if (is_array($value)) {
                    $newQuery[] = [
                         "match" => $value,
                    ];
                } else {
                    $newQuery[] = [
                         "match" => [
                              $term => $value,
                         ],
                    ];
                }
            }
        }

        return $newQuery;
    }

    /**
     * Create a range term
     *
     * @param array  $query
     * @param string $from
     * @param bool   $makeNested
     *
     * @return array
     */
    private function range(array $query, string $from = 'filter', $makeNested = true)
    {
        $newQuery = [];

        foreach ($query as $term => $value) {
            if ($this->isNested($term) && $makeNested) {
                $result     = $this->makeNested($term, 'range', $from, [$term => $value], false);
                $newQuery[] = $result;
            } else {
                $newQuery[] = [
                     "range" => [
                          $term => $value,
                     ],
                ];
            }
        }

        return $newQuery;
    }

    /**
     * Create a nested term for a nested field
     *
     * @param string       $field   The field which must be searched on it
     * @param string       $term    The search term (like match, range, ...) which must be created
     * @param string       $wrapper The wrapper which must encapsulate the nested term
     * @param array|string $data    Data of the given field
     * @param bool         $negate  Either negate the search term or not
     *
     * @return array
     */
    private function makeNested(string $field, string $term, string $wrapper, $data, bool $negate = true): array
    {
        [$method, $scope] = $this->getESMethodAndScope($wrapper);
        if ($negate) {
            $method = $this->negateMethod($method);
        }

        if (is_string($data)) {
            $data = [$field => $data];
        }

        $nested = $this->createSection($term, $data, false);
        if (empty(array_values($nested))) {
            return [];
        }

        return [
             'nested' => [
                  'path'  => substr($field, 0, strpos($field, '.')),
                  'query' => [
                       $scope => [
                            $method => $nested,
                       ],
                  ],
             ],
        ];
    }

    /**
     * Populate a given query placeholders with the given data
     *
     * @param array $query
     * @param array $data
     *
     * @return array
     */
    private function populatePlaceHoldersWithData(array $query, array $data): array
    {
        $newQuery = [];

        foreach ($query as $term => $value) {
            if (is_array($value)) {
                $value = $this->populatePlaceHoldersWithData($value, $data);
                if ($value) {
                    $newQuery[$term] = $value;
                }
            } else {
                if ($this->isPlaceHolder($value)) {
                    $value = $this->getPlaceHolderVariable($value);
                    if (isset($data[$value])) {
                        $value = $data[$value];
                        if (is_array($value) && Arr::isNonAssociative($value)) {
                            $value = array_map(
                                 function ($value) use ($term) {
                                     return [$term => $value];
                                 },
                                 $value
                            );

                        }

                        $newQuery[$term] = $value;
                    }
                } else {
                    $newQuery[$term] = $value;
                }
            }
        }

        return $newQuery;
    }

    /**
     * Indicate the term is a place holder or not
     *
     * @param string $str
     *
     * @return bool
     */
    private function isPlaceHolder(string $str): bool
    {
        return $str && $str[0] == '$';
    }

    /**
     * Return the behind variable of a placeholder
     *
     * @param string $term
     *
     * @return string
     */
    private function getPlaceHolderVariable(string $term): string
    {
        return substr($term, 1);
    }

    /**
     * Return the query data
     *
     * @return array
     */
    private function getQueryData(): array
    {
        return $this->query ?? [];
    }

    /**
     * Indicate that a field is nested or not
     *
     * @param string $field
     *
     * @return bool
     */
    private function isNested(string $field): bool
    {
        return strpos((string)$field, '.') !== false;
    }

    /**
     * Indicate that a term is an ElasticSearch term or not
     *
     * @param string $term
     *
     * @return bool
     */
    private function isESKeyword(string $term): bool
    {
        return in_array($term, static::ELASTIC_KEYWORDS);
    }

    /**
     * Return the method and its parent scope of a given ElasticSearch term
     *
     * @param string $term
     *
     * @return array
     */
    private function getESMethodAndScope(string $term): array
    {
        switch ($term) {
            case 'must':
                return ['must', 'bool'];
            case 'should':
                return ['should', 'bool'];
            case 'filter':
                return ['filter', 'bool'];
            default:
                return [$term, null];
        }
    }

    /**
     * Negate a method
     *
     * @param string $method
     *
     * @return string
     */
    private function negateMethod(string $method): string
    {
        $negateMap = [
             'must'   => 'should',
             'should' => 'must',
        ];

        return $negateMap[$method];
    }
}
