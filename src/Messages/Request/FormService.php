<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Request;

/**
 * Class FormService
 * @package FluencePrototype\Http\Messages\Request
 */
class FormService
{

    /**
     * @param string $name
     * @param int $filter
     * @param bool $requireAsArray
     * @return bool|int|string|array|null
     */
    private function getSanitizedInputValue(string $name, int $filter, bool $requireAsArray = false): null|bool|int|string|array
    {
        if ($requireAsArray) {
            if ($array = filter_input(type: INPUT_POST, var_name: $name, filter: $filter, options: FILTER_REQUIRE_ARRAY)) {
                return match ($filter) {
                    FILTER_UNSAFE_RAW => array_map(callback: 'trim', array: $array),
                    FILTER_SANITIZE_NUMBER_INT => array_map(callback: 'intval', array: $array),
                    FILTER_VALIDATE_BOOL => array_map(callback: 'boolval', array: $array)
                };
            }

            return null;
        }

        if ($value = filter_input(type: INPUT_POST, var_name: $name, filter: $filter)) {
            return match ($filter) {
                FILTER_SANITIZE_EMAIL, FILTER_UNSAFE_RAW, FILTER_SANITIZE_URL => trim(string: $value),
                FILTER_SANITIZE_NUMBER_INT => (int)$value,
                FILTER_VALIDATE_BOOL => (bool)$value
            };
        }

        return null;
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
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getEmail(string $name): null|string
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_EMAIL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getUrl(string $name): null|string
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_URL);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getString(string $name): null|string
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_UNSAFE_RAW);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getStringArray(string $name): null|array
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_UNSAFE_RAW, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return int|null
     */
    public function getInteger(string $name): null|int
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getIntegerArray(string $name): null|array
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_SANITIZE_NUMBER_INT, requireAsArray: true);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function getBoolean(string $name): bool
    {
        return (bool)FormService::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL);
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getBooleanArray(string $name): null|array
    {
        return FormService::getSanitizedInputValue(name: $name, filter: FILTER_VALIDATE_BOOL, requireAsArray: true);
    }

}