<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Http\Messages\iResponse;
use FluencePrototype\Http\PathService;

class Redirect implements iResponse
{

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