<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Methods;

use FluencePrototype\Http\Messages\iResponse;

/**
 * Interface iDelete
 * @package FluencePrototype\Http\Methods
 */
interface iDelete
{

    /**
     * @return iResponse
     */
    public function delete(): iResponse;

}