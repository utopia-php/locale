<?php

namespace Utopia\Locale;

use Exception;

class Locale
{
    /**
     * @var array
     */
    static protected $language = [];

    /**
     * Throw Exceptions?
     *
     * @var bool
     */
    static public $exceptions = true;

    /**
     * Default Locale
     *
     * @var null
     */
    static public $default = null;

    /**
     * Set New Locale
     *
     * @param string $name
     * @param array $language
     * @return array
     */
    static public function setLanguage($name, array $language) //TODO add support for lazy load to memory
    {
        if(empty(self::$default)) {
            self::$default = $name;
        }

        return self::$language[$name] = $language;
    }

    /**
     * Change Default Locale
     *
     * @param $name
     * @throws Exception
     */
    static public function setDefault($name)
    {
        if(!\array_key_exists($name, self::$language)) {
            throw new Exception('Locale not found');
        }

        self::$default = $name;
    }

    /**
     * Get Text by Locale
     *
     * @param $key
     * @param null $default
     * @return mixed
     * @throws Exception
     */
    static public function getText($key, $default = null) {
        $default = (\is_null($default)) ? '{{' . $key . '}}' : $default;

        if(!\array_key_exists($key, self::$language[self::$default])) {
            if(self::$exceptions) {
                throw new Exception('Key named "' . $key . '" not found');
            }

            return $default;
        }

        return self::$language[self::$default][$key];
    }
}