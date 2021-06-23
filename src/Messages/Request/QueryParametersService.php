<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Request;

/**
 * Class QueryParametersService
 * @package FluencePrototype\Http\Messages\Request
 */
class QueryParametersService
{

    private array $queryParameters = [];

    /**
     * QueryParametersService constructor.
     * @param array $queryParameters
     */
    public function __construct(array $queryParameters)
    {
        $this->queryParameters = $queryParameters;
    }

    /**
     * @param string $key
     * @return array|string|null
     */
    public function getParameter(string $key): array|string|null
    {
        if (isset($this->queryParameters[$key])) {
            return $this->queryParameters[$key];
        }

        return null;
    }

}