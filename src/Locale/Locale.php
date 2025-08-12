<?php

namespace Utopia\Locale;

use Exception;

class Locale
{
    /**
     * @var array<string, array<string, string>>
     */
    protected static $language = [];

    /**
     * Throw Exceptions?
     *
     * @var bool
     */
    public static $exceptions = true;

    /**
     * Default Locale
     *
     * @var string
     */
    public $default;

    /**
     * Fallback locale. Used when specific or default locale is missing translation.
     * Should always be set to locale that includes all translations.
     *
     * @var string|null
     */
    public $fallback = null;

    /**
     * Set New Locale from an array
     *
     * @param  string  $name
     * @param  array<string, string>  $translations
     */
    public static function setLanguageFromArray(string $name, array $translations): void //TODO add support for lazy load to memory
    {
        self::$language[$name] = $translations;
    }

    /**
     * Set New Locale from JSON file
     *
     * @param  string  $name
     * @param  string  $path
     */
    public static function setLanguageFromJSON(string $name, string $path): void
    {
        if (! file_exists($path) && self::$exceptions) {
            throw new Exception('Translation file not found.');
        }

        /** @var array<string, string> $translations */
        $translations = json_decode(file_get_contents($path) ?: '', true);
        self::$language[$name] = $translations;
    }

    public function __construct(string $default)
    {
        if (! \array_key_exists($default, self::$language) && self::$exceptions) {
            throw new Exception('Locale not found');
        }

        $this->default = $default;
    }

    /**
     * Change fallback Locale
     *
     * @param $name
     *
     * @throws Exception
     */
    public function setFallback(string $name): self
    {
        if (! \array_key_exists($name, self::$language) && self::$exceptions) {
            throw new Exception('Locale not found');
        }

        $this->fallback = $name;

        return $this;
    }

    /**
     * Change Default Locale
     *
     * @param $name
     *
     * @throws Exception
     */
    public function setDefault(string $name): self
    {
        if (! \array_key_exists($name, self::$language) && self::$exceptions) {
            throw new Exception('Locale not found');
        }

        $this->default = $name;

        return $this;
    }

    /**
     * Get Text by Locale
     *
     * @param  string  $key
     * @param  array<string, string|int>  $placeholders
     * @return mixed
     *
     * @throws Exception
     */
    public function getText(string $key, array $placeholders = [])
    {
        $default = (self::$language[$this->fallback ?? ''] ?? [])[$key] ?? '{{'.$key.'}}';

        if (! \array_key_exists($key, self::$language[$this->default])) {
            if (self::$exceptions) {
                throw new Exception('Key named "'.$key.'" not found');
            }

            return $default;
        }

        $translation = self::$language[$this->default][$key];

        foreach ($placeholders as $placeholderKey => $placeholderValue) {
            $translation = str_replace('{{'.$placeholderKey.'}}', (string) $placeholderValue, $translation);
        }

        return $translation;
    }
}
