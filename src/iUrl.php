<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

/**
 * Interface iUrl
 * @package FluencePrototype\Http
 */
interface iUrl
{

    /**
     * @return string
     */
    public function getProtocol(): string;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return int
     */
    public function getPort(): int;

    /**
     * @return string|null
     */
    public function getUsername(): null|string;

    /**
     * @return string|null
     */
    public function getPassword(): null|string;

    /**
     * @return string|null
     */
    public function getPath(): null|string;

    /**
     * @return string|null
     */
    public function getQuery(): null|string;

    /**
     * @return array
     */
    public function getQueryParameters(): array;

    /**
     * @return string|null
     */
    public function getFragment(): null|string;

}