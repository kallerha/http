<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages;

/**
 * Interface iRequest
 * @package FluencePrototype\Http\Messages
 */
interface iRequest
{

    /**
     * @return string
     */
    public function getSubdomain(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return array
     */
    public function getQueryParameters(): array;

    /**
     * @return string
     */
    public function getMethod(): string;

}