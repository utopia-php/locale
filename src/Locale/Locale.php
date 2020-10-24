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
     * @var string
     */
    public $default;

    /**
     * Set New Locale
     *
     * @param string $name
     * @param array $language
     */
    static public function setLanguage(string $name, array $language): void //TODO add support for lazy load to memory
    {
        self::$language[$name] = $language;
    }

    public function __construct(string $default)
    {
        if (!\array_key_exists($default, self::$language)) {
            throw new Exception('Locale not found');
        }

        $this->default = $default;
    }

    /**
     * Change Default Locale
     *
     * @param $name
     * @throws Exception
     */
    public function setDefault(string $name): self
    {
        if (!\array_key_exists($name, self::$language)) {
            throw new Exception('Locale not found');
        }

        $this->default = $name;

        return $this;
    }

    /**
     * Get Text by Locale
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     * @throws Exception
     */
    public function getText(string $key, $default = null)
    {
        $default = (\is_null($default)) ? '{{' . $key . '}}' : $default;

        if (!\array_key_exists($key, self::$language[$this->default])) {
            if (self::$exceptions) {
                throw new Exception('Key named "' . $key . '" not found');
            }

            return $default;
        }

        return self::$language[$this->default][$key];
    }
}