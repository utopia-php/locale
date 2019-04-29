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
    public function setUp()
    {
        Locale::$exceptions = false; // Disable exceptions

        Locale::setLanguage('en-US', ['hello' => 'Hello','world' => 'World']); // Set English
        Locale::setLanguage('he-IL', ['hello' => 'שלום',]); // Set Hebrew
    }

    public function tearDown()
    {
    }

    public function testTexts()
    {
        $this->assertEquals('Hello', Locale::getText('hello'));
        $this->assertEquals('World', Locale::getText('world'));

        Locale::setDefault('he-IL');

        $this->assertEquals('שלום', Locale::getText('hello'));
        $this->assertEquals('empty', Locale::getText('world', 'empty'));

        Locale::$exceptions = true; // Enable exceptions

        try {
            Locale::getText('world', 'empty');
        } catch (\Throwable $exception) {
            $this->assertInstanceOf(Exception::class, $exception);
            return;
        }

        $this->fail('No exception was thrown');
    }
}
