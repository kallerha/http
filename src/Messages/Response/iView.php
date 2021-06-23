<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

/**
 * Interface iView
 * @package FluencePrototype\Http\Messages\Response
 */
interface iView
{

    /**
     * @param string $key
     * @param array|bool|float|int|object|string $value
     * @return iView
     */
    public function addData(string $key, array|bool|float|int|object|string $value): iView;

}