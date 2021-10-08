<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Http\Messages\iResponse;
use JetBrains\PhpStorm\NoReturn;

/**
 * Class EmptyResponse
 * @package FluencePrototype\Http\Messages\Response
 */
class Text implements iResponse
{

    /**
     * Text constructor.
     * @param string $text
     */
    public function __construct(
        private string $text
    )
    {
    }

    /**
     * @inheritDoc
     */
    #[NoReturn] public function render(): void
    {
        echo $this->text . PHP_EOL;
        exit;
    }

}