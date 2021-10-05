<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Request;

class RestDataService
{

    private null|object $jsonInput;

    public function __construct(null|object $jsonInput = null)
    {
        $this->jsonInput = $jsonInput ?? json_decode(file_get_contents('php://input'));
    }

    /**
     * @param string $name
     * @param int $filter
     * @param bool $requireAsArray
     * @return bool|int|string|array|null
     */
    private function getSanitizedInputValue(string $name, int $filter, bool $requireAsArray = false): null|bool|int|string|array
    {
        if (!isset($this->jsonInput->{$name})) {
            return null;
        }

        if ($requireAsArray) {
            $array = filter_var($this->jsonInput->{$name}, filter: $filter, options: FILTER_REQUIRE_ARRAY);

            return match ($filter) {
                FILTER_SANITIZE_STRING => array_map(callback: 'trim', array: $array),
                FILTER_SANITIZE_NUMBER_INT => array_map(callback: 'intval', array: $array),
                FILTER_VALIDATE_BOOL => array_map(callback: 'boolval', array: $array)
            };
        }

        $value = filter_var($this->jsonInput->{$name}, filter: $filter);

        return match ($filter) {
            FILTER_SANITIZE_EMAIL, FILTER_SANITIZE_STRING, FILTER_SANITIZE_URL => trim(string: $value),
            FILTER_SANITIZE_NUMBER_INT => (int)$value,
            FILTER_VALIDATE_BOOL => (bool)$value
        };
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getName(string $name): ?string
    {
        if ($string = $this->getString($name)) {
            return preg_replace('/\s+/', ' ', $string);
        }

        return null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getRaw(string $name): ?string
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getEmail(string $name): ?string
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getUrl(string $name): ?string
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_URL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getString(string $name): ?string
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_STRING);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getStringArray(string $name): ?array
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_STRING, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getInteger(string $name): ?int
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getIntegerArray(string $name): ?array
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getBoolean(string $name): bool
    {
        return (bool)self::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getBooleanArray(string $name): ?array
    {
        return self::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL, requireAsArray: true);
    }

    public function getObject(string $name): ?RestDataService
    {
        if (!isset($this->jsonInput->{$name})) {
            return null;
        }

        if (!is_object($this->jsonInput->{$name})) {
            return null;
        }

        return new RestDataService($this->jsonInput->{$name});
    }

}