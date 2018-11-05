<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

namespace App\Tests\Util;

use App\Util\StringUtil;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class StringUtilTest extends TestCase
{
    /**
     * @covers \App\Util\StringUtil::camel()
     */
    public function testCamel()
    {
        $camel = new StringUtil();
        $this->assertEquals('someText', $camel->camel('some_text'));
        $this->assertEquals('someTExt', $camel->camel('some_t_ext'));
        $this->assertEquals('sometext', $camel->camel('sometext'));
        $this->assertEquals('someText', $camel->camel('Some_text'));
        $this->assertEquals('someText', $camel->camel('someText'));
    }

    /**
     * @covers \App\Util\StringUtil::snake()
     */
    public function testSnake()
    {
        $snake = new StringUtil();
        $this->assertEquals('some_text', $snake->snake('someText'));
        $this->assertEquals('some_t_ext', $snake->snake('someTExt'));
        $this->assertEquals('sometext', $snake->snake('sometext'));
        $this->assertEquals('some_text', $snake->snake('someText'));
    }

    /**
     * @covers \App\Util\StringUtil::ucfirst()
     */
    public function testUcfirst()
    {
        $ucfirst = new StringUtil();
        $this->assertEquals('SomeText', $ucfirst->ucfirst('someText'));
        $this->assertEquals('SomeTExt', $ucfirst->ucfirst('someTExt'));
        $this->assertEquals('Sometext', $ucfirst->ucfirst('Sometext'));
        $this->assertEquals('Some_text', $ucfirst->ucfirst('some_text'));
    }

    /**
     * @covers \App\Util\StringUtil::hyphen()
     */
    public function testHyphen()
    {
        $snake = new StringUtil();
        $this->assertEquals('some-text', $snake->hyphen('someText'));
        $this->assertEquals('some-t-ext', $snake->hyphen('someTExt'));
        $this->assertEquals('sometext', $snake->hyphen('sometext'));
        $this->assertEquals('some-text', $snake->hyphen('someText'));
    }
}
