<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages;

use FluencePrototype\Http\HttpUrl;
use FluencePrototype\Http\iUrl;

/**
 * Class Request
 * @package FluencePrototype\Http\Messages
 */
class Request implements iRequest
{

    private iUrl $httpUrl;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->httpUrl = HttpUrl::createFromCurrentUrl();
    }

    /**
     * @inheritDoc
     */
    public function getSubdomain(): string
    {
        $hostArray = explode(separator: '.', string: $this->httpUrl->getHost());

        return $hostArray[0];
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return str_replace(search: $_ENV['SCHEME'] . '://' . $this->getSubdomain() . '.' . $_ENV['HOST'], replace: '', subject: $this->httpUrl->getProtocol() . '://' . $this->httpUrl->getHost() . $this->httpUrl->getPath());
    }

    /**
     * @inheritDoc
     */
    public function getQueryParameters(): array
    {
        return $this->httpUrl->getQueryParameters();
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return strtolower(string: filter_input(type: INPUT_SERVER, var_name: 'REQUEST_METHOD', filter: FILTER_SANITIZE_STRING));
    }

}