<?php

declare(strict_types=1);

namespace FluencePrototype\Http;

use Attribute;
use FluencePrototype\Http\Messages\Response\StatusCodes;

/**
 *
 */
#[Attribute(Attribute::TARGET_CLASS)]
class TimeLimit
{

    /**
     * @param int $seconds
     */
    public function __construct(int $seconds)
    {
        register_shutdown_function(callback: function (): void {
            if (($error = error_get_last()) &&
                str_contains(haystack: $error['message'], needle: 'Maximum execution time')) {
                http_response_code(response_code: StatusCodes::GATEWAY_TIMEOUT);

                exit;
            }
        });

        set_time_limit(seconds: $seconds);
    }

}