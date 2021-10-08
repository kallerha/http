<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages;

use Exception;
use FluencePrototype\Http\Messages\Response\StatusCodes;
use JetBrains\PhpStorm\Pure;
use Throwable;

/**
 * Class NotFoundException
 * @package FluencePrototype\Http\Messages
 */
class NotFoundException extends Exception
{

    /**
     * MethodNotAllowedException constructor.
     * @param string $message
     * @param Throwable|null $previous
     */
    #[Pure] public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message, StatusCodes::NOT_FOUND, $previous);
    }

}