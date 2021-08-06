<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Http\Messages\iResponse;
use FluencePrototype\Http\PathService;

/**
 * Class Redirect
 * @package FluencePrototype\Http\Messages\Response
 */
class Redirect implements iResponse
{

    /**
     * Redirect constructor.
     * @param string $to
     * @param int $statusCode
     * @param array $parameters
     */
    public function __construct(
        private string $to,
        private int $statusCode,
        private array $parameters = []
    )
    {

    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        $pathService = new PathService();

        if ($path = $pathService->getPathByName(name: $this->to, parameters: $this->parameters)) {
            header(header: 'Location: ' . $path, replace: true, response_code: $this->statusCode);

            exit;
        }
    }

}