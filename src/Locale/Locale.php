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
     * Set New Locale from an array
     *
     * @param string $name
     * @param array $translations
     */
    static public function setLanguageFromArray(string $name, array $translations): void //TODO add support for lazy load to memory
    {
        self::$language[$name] = $translations;
    }

    /**
     * Set New Locale from JSON file
     *
     * @param string $name
     * @param string $path
     */
    static public function setLanguageFromJSON(string $name, string $path): void 
    {
        if (!file_exists($path)) {
            throw new Exception('Translation file not found.');
        }
        
        $translations = json_decode(file_get_contents($path),true);
        self::$language[$name] = $translations;
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
    public function getText(string $key, $placeholders = [])
    {
        $default = '{{' . $key . '}}';

        if (!\array_key_exists($key, self::$language[$this->default])) {
            if (self::$exceptions) {
                throw new Exception('Key named "' . $key . '" not found');
            }

            return $default;
        }

        $translation = self::$language[$this->default][$key];

        foreach ($placeholders as $placeholderKey => $placeholderValue) {
            $translation = str_replace('{{' . $placeholderKey . '}}', $placeholderValue, $translation);
        }

        return $translation;
    }
}