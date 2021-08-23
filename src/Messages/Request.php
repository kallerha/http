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

    /**
     * @inheritDoc
     */
    public function getIp(): ?string
    {
        if ($ip = filter_input(INPUT_SERVER, 'HTTP_CF_CONNECTING_IP', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_FORWARDED_FOR', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_FORWARDED', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_X_CLUSTER_CLIENT_IP', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'HTTP_X_REAL_IP', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE)) {
            return $ip;
        }

        if ($ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP, FILTER_NULL_ON_FAILURE)) {
            return $ip;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUserAgent(): string
    {
        return trim(filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE));
    }

}