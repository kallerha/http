<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Request;

interface iInput
{

    public function getName(string $name): ?string;

    public function getRaw(string $name): ?string;

    public function getEmail(string $name): ?string;

    public function getUrl(string $name): ?string;

    public function getString(string $name): ?string;

    public function getStringArray(string $name): ?array;

    public function getInteger(string $name): ?int;

    public function getIntegerArray(string $name): ?array;

    public function getBoolean(string $name): ?bool;

    public function getBooleanArray(string $name): ?array;

}