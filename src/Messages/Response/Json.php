<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Http\Messages\iResponse;
use JsonException;

/**
 * Class View
 * @package FluencePrototype\Http\Messages\Response
 */
class Json implements iResponse
{

    /**
     * View constructor.
     * @param array|object $data
     * @param int $responseCode
     */
    public function __construct(
        private array|object|string $data,
        private int                 $responseCode = StatusCodes::OK
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        try {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(response_code: $this->responseCode);

            echo str_replace('\t', ' ', json_encode($this->data,
                JSON_INVALID_UTF8_SUBSTITUTE |
                JSON_PARTIAL_OUTPUT_ON_ERROR |
                JSON_PRESERVE_ZERO_FRACTION |
                JSON_UNESCAPED_LINE_TERMINATORS |
                JSON_UNESCAPED_UNICODE |
                JSON_THROW_ON_ERROR
            ));

            exit;
        } catch (JsonException $e) {
        }
    }

}