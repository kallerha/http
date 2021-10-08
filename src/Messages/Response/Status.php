<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Http\Messages\iResponse;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class Status
 * @package FluencePrototype\Http\Messages\Response
 */
class Status implements iResponse
{

    /**
     * Status constructor.
     * @param int $statusCode
     */
    public function __construct(
        private int $statusCode
    )
    {

    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function render(): void
    {
        http_response_code($this->statusCode);

        exit;
    }

}