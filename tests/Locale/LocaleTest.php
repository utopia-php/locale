<?php
/**
 * Utopia PHP Framework
 *
 * @package Locale
 * @subpackage Tests
 *
 * @link https://github.com/utopia-php/framework
 * @author Eldad Fux <eldad@appwrite.io>
 * @version 1.0 RC4
 * @license The MIT License (MIT) <http://www.opensource.org/licenses/mit-license.php>
 */

namespace Utopia\Tests;

use Exception;
use Utopia\Locale\Locale;
use PHPUnit\Framework\TestCase;

class LocaleTest extends TestCase
{
    /**
     * @var Locale
     */
    protected $locale = null;

    public function setUp(): void
    {
        Locale::$exceptions = false; // Disable exceptions

        Locale::setLanguageFromArray('en-US', ['hello' => 'Hello','world' => 'World']); // Set English
        Locale::setLanguageFromArray('he-IL', ['hello' => 'שלום',]); // Set Hebrew
        Locale::setLanguageFromJSON('hi-IN', realpath(__DIR__.'/../hi-IN.json')); // Set Hindi
    }

    public function tearDown(): void
    {
    }

    public function testTexts()
    {
        $locale = new Locale('en-US');

        $this->assertEquals('Hello', $locale->getText('hello'));
        $this->assertEquals('World', $locale->getText('world'));

        $locale->setDefault('hi-IN');

        $this->assertEquals('Namaste', $locale->getText('hello'));
        $this->assertEquals('Duniya', $locale->getText('world'));

        $locale->setDefault('he-IL');

        $this->assertEquals('שלום', $locale->getText('hello'));
        $this->assertEquals('empty', $locale->getText('world', 'empty'));

        Locale::$exceptions = true; // Enable exceptions

        try {
            $locale->getText('world', 'empty');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            return;
        }

        $this->fail('No exception was thrown');
    }
}
