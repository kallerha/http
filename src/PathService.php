<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

use FluencePrototype\Cache\Cache;
use FluencePrototype\Filesystem\Filesystem;

/**
 * Class PathService
 * @package FluencePrototype\Http
 */
class PathService
{

    private array $routeNamesCache = [];

    /**
     * @return array
     */
    public function getRouteNamesCache(): array
    {
        if (empty($this->routeNamesCache)) {
            $cache = new Cache();

            if (!$routeNamesCache = $cache->fetch(key: 'routeNamesCache')) {
                $filesystem = new Filesystem();
                $routeNamesCache = $cache->store(key: 'routeNamesCache', value: require $filesystem->getDirectoryPath() . DIRECTORY_SEPARATOR . 'route.names.cache.php');
            }

            $this->routeNamesCache = $routeNamesCache;
        }

        return $this->routeNamesCache;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string|null
     */
    public function getPathByName(string $name, array $parameters = []): ?string
    {
        $routeNamesCache = $this->getRouteNamesCache();

        if (isset($routeNamesCache[$name])) {
            if (!empty($parameters)) {
                $parameterKeys = array_map(callback: function (string $key) {
                    return ':' . $key;
                }, array: array_keys(array: $parameters));

                $parameterValues = array_values(array: $parameters);

                return $_ENV['SCHEME'] . '://' . $routeNamesCache[$name]['subdomain'] . '.' . $_ENV['HOST'] . str_replace(search: $parameterKeys, replace: $parameterValues, subject: $routeNamesCache[$name]['path']);
            }

            return $_ENV['SCHEME'] . '://' . $routeNamesCache[$name]['subdomain'] . '.' . $_ENV['HOST'] . $routeNamesCache[$name]['path'];
        }

        return null;
    }

}