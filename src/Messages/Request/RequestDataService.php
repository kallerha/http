<?php
declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Request;

class RequestDataService
{

    public function getRaw(string $name): null|string
    {
        if (!array_key_exists($name, $_SERVER)) {
            return null;
        }

        return (string)$_SERVER[$name];
    }

}