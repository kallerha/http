<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Methods;

use FluencePrototype\Http\Messages\iResponse;

/**
 * Interface iPatch
 * @package FluencePrototype\Http\Methods
 */
interface iPatch
{

    /**
     * @return iResponse
     */
    public function patch(): iResponse;

}