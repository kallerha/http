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

    /**
     * @return string
     */
    public function getCurrentUrlWithoutQueryAndFragment(): string
    {
        /** @var HttpUrl $httpUrl */
        $httpUrl = HttpUrl::createFromCurrentUrl();
        $httpUrl->setQuery(null);
        $httpUrl->setFragment(null);

        return $httpUrl->__toString();
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return bool
     */
    public function isCurrentPathByName(string $name, array $parameters = []): bool
    {
        $currentUrl = $this->getCurrentUrl();

        if ($path = $this->getPathByName($name, $parameters)) {
            return $path === $currentUrl;
        }

        return false;
    }

    /**
     * @param string $subdomain
     * @return string
     */
    public function getHostWithSubdomain(string $subdomain): string
    {
        return $_ENV['SCHEME'] . '://' . $subdomain . '.' . $_ENV['HOST'];
    }

}