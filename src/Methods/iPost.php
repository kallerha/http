<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Methods;

use FluencePrototype\Http\Messages\iResponse;

/**
 * Interface iPost
 * @package FluencePrototype\Http\Methods
 */
interface iPost
{

    /**
     * @return iResponse
     */
    public function post(): iResponse;

}