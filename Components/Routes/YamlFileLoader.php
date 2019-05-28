<?php

namespace Espricho\Components\Routes;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Routing\Loader\YamlFileLoader as BaseYamlFileLoader;

use function sprintf;

class YamlFileLoader extends BaseYamlFileLoader
{
    private static $availableKeys = [
         'type',
         'path',
         'host',
         'prefix',
         'methods',
         'schemes',
         'options',
         'resource',
         'defaults',
         'namespace',
         'condition',
         'controller',
         'middleware',
         'name_prefix',
         'requirements',
         'trailing_slash_on_root',
    ];

    private $yamlParser;

    /**
     * Loads a Yaml file.
     *
     * @param string      $file A Yaml file path
     * @param string|null $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     * @throws \InvalidArgumentException When a route can't be parsed because YAML is invalid
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        if (!stream_is_local($path)) {
            throw new \InvalidArgumentException(sprintf('This is not a local file "%s".', $path));
        }

        if (!file_exists($path)) {
            throw new \InvalidArgumentException(sprintf('File "%s" not found.', $path));
        }

        if (null === $this->yamlParser) {
            $this->yamlParser = new YamlParser();
        }

        try {
            $parsedConfig = $this->yamlParser->parseFile($path, Yaml::PARSE_CONSTANT);
        } catch (ParseException $e) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $path), 0, $e);
        }

        $collection = new RouteCollection();
        $collection->addResource(new FileResource($path));

        // empty file
        if (null === $parsedConfig) {
            return $collection;
        }

        // not an array
        if (!\is_array($parsedConfig)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $path));
        }

        foreach ($parsedConfig as $name => $config) {
            $this->validate($config, $name, $path);

            if (isset($config['resource'])) {
                $this->parseImport($collection, $config, $path, $file);
            } else {
                $this->parseRoute($collection, $name, $config, $path);
            }
        }

        return $collection;
    }

    /**
     * Parses a route and adds it to the RouteCollection.
     *
     * @param RouteCollection $collection A RouteCollection instance
     * @param string          $name       Route name
     * @param array           $config     Route definition
     * @param string          $path       Full path of the YAML file being processed
     */
    protected function parseRoute($collection, $name, array $config, $path)
    {
        $defaults     = isset($config['defaults']) ? $config['defaults'] : [];
        $requirements = isset($config['requirements']) ? $config['requirements'] : [];
        $options      = isset($config['options']) ? $config['options'] : [];
        $host         = isset($config['host']) ? $config['host'] : '';
        $schemes      = isset($config['schemes']) ? $config['schemes'] : [];
        $methods      = isset($config['methods']) ? $config['methods'] : [];
        $condition    = isset($config['condition']) ? $config['condition'] : null;

        foreach ($requirements as $placeholder => $requirement) {
            if (\is_int($placeholder)) {
                @trigger_error(sprintf('A placeholder name must be a string (%d given). Did you forget to specify the placeholder key for the requirement "%s" of route "%s" in "%s"?', $placeholder, $requirement, $name, $path), E_USER_DEPRECATED);
            }
        }

        if (isset($config['controller'])) {
            if (isset($config['namespace'])) {
                $config['controller'] = sprintf("%s\\%s", $config['namespace'], $config['controller']);
            }

            $defaults['_controller'] = $config['controller'];
        }

        if(isset($config['middleware'])) {
            $defaults['middleware'] = $config['middleware'];
        }

        if (\is_array($config['path'])) {
            $route = new Route('', $defaults, $requirements, $options, $host, $schemes, $methods, $condition);

            foreach ($config['path'] as $locale => $path) {
                $localizedRoute = clone $route;
                $localizedRoute->setDefault('_locale', $locale);
                $localizedRoute->setDefault('_canonical_route', $name);
                $localizedRoute->setPath($path);
                $collection->add($name . '.' . $locale, $localizedRoute);
            }
        } else {
            $route = new Route($config['path'], $defaults, $requirements, $options, $host, $schemes, $methods, $condition);
            $collection->add($name, $route);
        }
    }

    /**
     * Validates the route configuration.
     *
     * @param array  $config A resource config
     * @param string $name   The config key
     * @param string $path   The loaded file path
     *
     * @throws \InvalidArgumentException If one of the provided config keys is not supported,
     *                                   something is missing or the combination is nonsense
     */
    protected function validate($config, $name, $path)
    {
        if (!\is_array($config)) {
            throw new \InvalidArgumentException(sprintf('The definition of "%s" in "%s" must be a YAML array.', $name, $path));
        }
        if ($extraKeys = array_diff(array_keys($config), self::$availableKeys)) {
            throw new \InvalidArgumentException(sprintf('The routing file "%s" contains unsupported keys for "%s": "%s". Expected one of: "%s".', $path, $name, implode('", "', $extraKeys), implode('", "', self::$availableKeys)));
        }
        if (isset($config['resource']) && isset($config['path'])) {
            throw new \InvalidArgumentException(sprintf('The routing file "%s" must not specify both the "resource" key and the "path" key for "%s". Choose between an import and a route definition.', $path, $name));
        }
        if (!isset($config['resource']) && isset($config['type'])) {
            throw new \InvalidArgumentException(sprintf('The "type" key for the route definition "%s" in "%s" is unsupported. It is only available for imports in combination with the "resource" key.', $name, $path));
        }
        if (!isset($config['resource']) && !isset($config['path'])) {
            throw new \InvalidArgumentException(sprintf('You must define a "path" for the route "%s" in file "%s".', $name, $path));
        }
        if (isset($config['controller']) && isset($config['defaults']['_controller'])) {
            throw new \InvalidArgumentException(sprintf('The routing file "%s" must not specify both the "controller" key and the defaults key "_controller" for "%s".', $path, $name));
        }
    }
}
