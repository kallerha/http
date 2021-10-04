<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

/**
 * Class StatusCodes
 * @package FluencePrototype\Http\Messages\Response
 */
class StatusCodes
{

    public const OK = 200;
    public const FOUND = 302;
    public const SEE_OTHER = 303;
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;

}