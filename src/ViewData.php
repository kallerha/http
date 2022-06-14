<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

class ViewData
{

    public function __construct(
        private array $data
    )
    {
    }

    public function __get(string $name): mixed
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return null;
    }

}