<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

use Stringable;

/**
 * Class HttpUrl
 * @package FluencePrototype\Http
 */
class HttpUrl implements iUrl, Stringable
{

    /**
     * HttpUrl constructor.
     * @param string $protocol
     * @param string $host
     * @param int $port
     * @param string|null $username
     * @param string|null $password
     * @param string|null $query
     * @param string|null $path
     * @param string|null $fragment
     */
    public function __construct(
        private string $protocol,
        private string $host,
        private int $port = 80,
        private ?string $username = null,
        private ?string $password = null,
        private ?string $query = null,
        private ?string $path = null,
        private ?string $fragment = null
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol): void
    {
        $this->protocol = $protocol;
    }

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @inheritDoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string|null $path
     */
    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string|null $query
     */
    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParameters(): array
    {
        if ($this->query) {
            parse_str(string: $this->query, result: $queryParameters);

            return $queryParameters;
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function getFragment(): ?string
    {
        return $this->fragment;
    }

    /**
     * @param string|null $fragment
     */
    public function setFragment(?string $fragment): void
    {
        $this->fragment = $fragment;
    }

    /**
     * @param string $url
     * @return iUrl|null
     */
    public static function createFromUrl(string $url): ?iUrl
    {
        if (!filter_var(value: $url, filter: FILTER_VALIDATE_URL)) {
            return null;
        }

        $scheme = '';
        $host = '';
        $port = 80;
        $user = null;
        $pass = null;
        $query = null;
        $path = null;
        $fragment = null;

        extract(array: parse_url(url: $url));

        return new HttpUrl(protocol: $scheme, host: $host, port: $port, username: $user, password: $pass, query: $query, path: $path, fragment: $fragment);
    }

    /**
     * @return iUrl
     */
    public static function createFromCurrentUrl(): iUrl
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://';
        $httpHost = filter_input(type: INPUT_SERVER, var_name: 'HTTP_HOST', filter: FILTER_SANITIZE_STRING);
        $requestUri = filter_input(type: INPUT_SERVER, var_name: 'REQUEST_URI', filter: FILTER_SANITIZE_STRING);
        $url = $protocol . $httpHost . $requestUri;

        return self::createFromUrl(url: $url);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        $url = $this->protocol . '://';

        if ($this->username) {
            $url .= $this->username;
        }

        if ($this->password) {
            $url .= ':' . $this->password;
        }

        if ($this->username || $this->password) {
            $url .= '@';
        }

        $url .= $this->host;

        if ($this->port && $this->port !== 80) {
            $url .= ':' . $this->port;
        }

        $url .= $this->path;

        if ($this->query) {
            $url .= '?' . $this->query;
        }

        if ($this->fragment) {
            $url .= '#' . $this->fragment;
        }

        return $url;
    }

}