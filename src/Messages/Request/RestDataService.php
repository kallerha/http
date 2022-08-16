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
    private function getSanitizedInputValue(string $name, int $filter, bool $requireAsArray = false): null|bool|int|float|string|array
    {
        if (!isset($this->jsonInput->{$name})) {
            return null;
        }

        if ($requireAsArray) {
            $array = filter_var($this->jsonInput->{$name}, filter: $filter, options: FILTER_REQUIRE_ARRAY);

            return match ($filter) {
                FILTER_UNSAFE_RAW => array_map(callback: 'trim', array: $array),
                FILTER_SANITIZE_NUMBER_INT => array_map(callback: 'intval', array: $array),
                FILTER_VALIDATE_BOOL => array_map(callback: 'boolval', array: $array)
            };
        }

        if ($filter === FILTER_SANITIZE_NUMBER_FLOAT) {
            $value = filter_var($this->jsonInput->{$name}, filter: $filter, options: FILTER_FLAG_ALLOW_FRACTION);
        } else {
            $value = filter_var($this->jsonInput->{$name}, filter: $filter);
        }

        return match ($filter) {
            FILTER_SANITIZE_EMAIL, FILTER_UNSAFE_RAW, FILTER_SANITIZE_URL => trim(string: $value),
            FILTER_SANITIZE_NUMBER_INT => (int)$value,
            FILTER_SANITIZE_NUMBER_FLOAT => (float)$value,
            FILTER_VALIDATE_BOOL => (bool)$value
        };
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getName(string $name): null|string
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
    public function getRaw(string $name): null|string
    {
        if (isset($this->jsonInput->{$name})) {
            return $this->jsonInput->{$name};
        }

        return null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getEmail(string $name): null|string
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getUrl(string $name): null|string
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_URL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getString(string $name): null|string
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_UNSAFE_RAW);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getStringArray(string $name): null|array
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_UNSAFE_RAW, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getInteger(string $name): null|int
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getIntegerArray(string $name): null|array
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return float|null
     */
    public function getFloat(string $name): null|float
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getBoolean(string $name): bool
    {
        return (bool)RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getBooleanArray(string $name): null|array
    {
        return RestDataService::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return RestDataService|null
     */
    public function getObject(string $name): null|RestDataService
    {
        if (!isset($this->jsonInput->{$name})) {
            return null;
        }

        if (!is_object($this->jsonInput->{$name})) {
            return null;
        }

        return new RestDataService($this->jsonInput->{$name});
    }


    /**
     * @param string $name
     * @return array<RestDataService>|null
     */
    public function getObjectArray(string $name): null|array
    {
        if (!isset($this->jsonInput->{$name})) {
            return null;
        }

        if (!is_array($this->jsonInput->{$name})) {
            return null;
        }

        return array_filter(array_map(function ($object) {
            if (!is_object($object)) {
                return null;
            }

            return new RestDataService($object);
        }, $this->jsonInput->{$name}));
    }

    public static function createFromEncodedJson(string $jsonString): RestDataService
    {
        return new RestDataService(json_decode($jsonString));
    }

}