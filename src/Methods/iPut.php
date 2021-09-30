<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Methods;

use FluencePrototype\Http\Messages\iResponse;

/**
 * Interface iPut
 * @package FluencePrototype\Http\Methods
 */
interface iPut
{

    /**
     * @return iResponse
     */
    public function put(): iResponse;

}