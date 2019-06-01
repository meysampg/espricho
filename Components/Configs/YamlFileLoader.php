<?php

namespace Espricho\Components\Configs;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Exception\ParseException;

use function in_array;
use function is_array;
use function is_string;

/**
 * Class YamlFileLoader Loads a given YAML file as a ConfigCollection
 *
 * @package Espricho\Components\System
 */
class YamlFileLoader extends FileLoader
{
    private $yamlParser;

    public function load($resource, $type = null): ConfigCollection
    {
        $path = $this->locator->locate($resource);

        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File "%s" not found.', $path));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new Parser();
        }

        try {
            $parsedConfig = $this->yamlParser->parseFile($path, Yaml::PARSE_CONSTANT);
        } catch (ParseException $e) {
            throw new InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $path), 0, $e);
        }

        $collection = new ConfigCollection();
        $collection->addResource(new FileResource($path));

        // empty file
        if (null === $parsedConfig) {
            return $collection;
        }

        // not an array
        if (!is_array($parsedConfig)) {
            throw new InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $path));
        }

        foreach ($parsedConfig as $name => $config) {
            $this->parseConfig($collection, $name, $config);
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null): bool
    {
        return is_string($resource) && in_array(
                  pathinfo($resource, PATHINFO_EXTENSION), [
                  'yml',
                  'yaml',
             ], true
             ) && (!$type || 'yaml' === $type);
    }

    /**
     * Parses a route and adds it to the RouteCollection.
     *
     * @param ConfigCollection $collection A ConfigCollection instance
     * @param string           $name       Config name
     * @param array            $config     Config definition
     */
    protected function parseConfig(
         ConfigCollection $collection,
         $name,
         ?array $config
    ) {
        if (!$config) {
            return;
        }

        $config = new Configuration($config);
        $collection->add($name, $config);
    }
}
