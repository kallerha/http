<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Methods;

use FluencePrototype\Http\Messages\iResponse;

/**
 * Interface iGet
 * @package FluencePrototype\Http\Methods
 */
interface iGet
{

    /**
     * @return iResponse
     */
    public function get(): iResponse;

}