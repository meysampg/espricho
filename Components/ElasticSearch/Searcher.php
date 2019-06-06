<?php

namespace Espricho\Components\ElasticSearch;

use ArrayAccess;
use Elasticsearch\Client;
use Psr\Log\LoggerInterface;
use Espricho\Components\Supports\ArrayCollection;
use Espricho\Components\Contracts\SearchInterface;
use Espricho\Components\Contracts\SearchableInterface;
use Espricho\Components\Contracts\SearchConditionInterface;

use function is_array;
use function is_object;
use function method_exists;

/**
 * Class Searcher implement SearchInterface for ElasticSearch engine
 *
 * @package Espricho\Components\ElasticSearch
 */
class Searcher implements SearchInterface
{
    /**
     * ElasticSearch client
     *
     * @var Client
     */
    protected $client;

    /**
     * Logger for log exceptions
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Searcher constructor.
     *
     * @param Client          $client
     * @param LoggerInterface $logger
     */
    public function __construct(Client $client, LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    /**
     * Client setter
     *
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Client getter
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @inheritDoc
     */
    public function searchFor(SearchConditionInterface $searchable): array
    {
        // TODO: Implement searchFor() method.
    }

    /**
     * @inheritDoc
     */
    public function updateIndexFor(SearchableInterface $searchable): bool
    {
        // TODO: implement a firm update method
        return $this->createIndexFor($searchable);
    }

    /**
     * @inheritDoc
     */
    public function createIndexFor(SearchableInterface $searchable): bool
    {
        $res = $this->getClient()->index($this->getParams($searchable));

        return $res['_version'] > 0;
    }

    /**
     * @inheritDoc
     */
    public function deleteIndexFor(SearchableInterface $searchable): bool
    {
        $params = $this->getParams($searchable);
        unset($params['body']);

        $res = $this->getClient()->delete($params);

        return $res['_id'] > 0;
    }

    /**
     * Return ready-to-use params of a searchable documents
     *
     * @param SearchableInterface $searchable
     *
     * @return array
     */
    protected function getParams(SearchableInterface $searchable): array
    {
        return [
             'index' => $searchable->searchIndex(),
             'type'  => '_doc',
             'id'    => $searchable->searchId(),
             'body'  => $this->getSearchData($searchable->searchData()),
        ];
    }

    /**
     * Return search data from given dataset
     *
     * @param array|ArrayCollection $data
     *
     * @return array
     */
    protected function getSearchData($data, string $prefix = ''): array
    {
        $newData = [];

        foreach ($data as $key => $value) {
            if (is_array($value) || $value instanceof ArrayAccess) {
                $newData[$prefix . $key] = $this->getSearchData($value, $key . '.');
            } elseif (is_object($value)) {
                if (method_exists($value, 'searchData')) {
                    $newData[] = $this->getSearchData($value->searchData());
                }
            } else {
                $newData[$prefix . $key] = $value;
            }
        }

        return $newData;
    }
}
