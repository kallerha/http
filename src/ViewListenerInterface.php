<?php
declare(strict_types=1);

namespace FluencePrototype\Http;

interface ViewListenerInterface
{

    public function listen(ViewData &$viewData): void;

}