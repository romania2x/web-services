<?php

namespace App\Constants;

abstract class AbstractEnum
{
    /**
     * @var array|null
     */
    protected static $cache = [];

    /**
     * @noinspection PhpDocMissingThrowsInspection
     * @param $value
     * @return string
     */
    public static function toString($value): string
    {
        $class = get_called_class();

        if (!isset(static::$cache[$class])) {
            static::$cache[$class] = array_flip((new \ReflectionClass($class))->getConstants());
        }
        if (!isset(static::$cache[$class][$value])) {
            throw new \InvalidArgumentException("{$class}: Unknown value {$value}");
        }
        return static::$cache[$class][$value];
    }
}
