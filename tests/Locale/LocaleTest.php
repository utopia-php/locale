<?php

namespace Utopia\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Utopia\Locale\Locale;

class LocaleTest extends TestCase
{
    /**
     * @var Locale
     */
    protected $locale = null;

    public function setUp(): void
    {
        Locale::$exceptions = false; // Disable exceptions

        Locale::setLanguageFromArray('en-US', ['hello' => 'Hello', 'world' => 'World', 'helloPlaceholder' => 'Hello {{name}} {{surname}}!', 'numericPlaceholder' => 'We have {{usersAmount}} users registered.', 'multiplePlaceholders' => 'Lets repeat: {{word}}, {{word}}, {{word}}']); // Set English
        Locale::setLanguageFromArray('he-IL', ['hello' => 'שלום']); // Set Hebrew
        Locale::setLanguageFromJSON('hi-IN', realpath(__DIR__.'/../hi-IN.json') ?: ''); // Set Hindi
    }

    public function tearDown(): void
    {
    }

    public function testTexts(): void
    {
        $locale = new Locale('en-US');

        $this->assertEquals('Hello', $locale->getText('hello'));
        $this->assertEquals('World', $locale->getText('world'));

        $locale->setDefault('hi-IN');

        $this->assertEquals('Namaste', $locale->getText('hello'));
        $this->assertEquals('Duniya', $locale->getText('world'));

        $locale->setDefault('he-IL');

        $this->assertEquals('שלום', $locale->getText('hello'));
        // $this->assertEquals('empty', $locale->getText('world', 'empty')); Has been removed in 0.5.0

        // Test placeholders
        $locale->setDefault('en-US');

        $this->assertEquals('Hello Matej Bačo!', $locale->getText('helloPlaceholder', [
            'name' => 'Matej',
            'surname' => 'Bačo',
        ]));
        $this->assertEquals('Hello Matej {{surname}}!', $locale->getText('helloPlaceholder', [
            'name' => 'Matej',
        ]));
        $this->assertEquals('Hello {{name}} {{surname}}!', $locale->getText('helloPlaceholder'));

        $this->assertEquals('We have 12 users registered.', $locale->getText('numericPlaceholder', [
            'usersAmount' => 6 + 6,
        ]));

        $this->assertEquals('Lets repeat: Appwrite, Appwrite, Appwrite', $locale->getText('multiplePlaceholders', [
            'word' => 'Appwrite',
        ]));

        // Test exceptions
        $locale->setDefault('he-IL');
        Locale::$exceptions = true;

        try {
            $locale->getText('world');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);

            return;
        }

        $this->fail('No exception was thrown');
    }
}
