<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

use Attribute;
use FluencePrototype\Http\Messages\Response\StatusCodes;

#[Attribute(Attribute::TARGET_CLASS)]
class TimeLimit
{

    public function __construct(int $seconds)
    {

        register_shutdown_function(function () {
            if ($error = error_get_last()) {
                if (str_contains($error['message'], 'Maximum execution time')) {
                    http_response_code(StatusCodes::GATEWAY_TIMEOUT);
                    exit;
                }
            }
        });

        set_time_limit(seconds: $seconds);

    }

}