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
declare(strict_types=1);
namespace App\Tests\Form;

use App\Form\Field\SettingsTransformer;
use PHPUnit\Framework\TestCase;

class SettingsTransformerTest extends TestCase
{
    /**
     * @covers \App\Form\Field\SettingsTransformer::transform
     */
    public function testTransform()
    {
        $settings = new SettingsTransformer();
        $this->assertNull($settings->transform([]));
        $this->assertNull($settings->transform(['dummy']));
        $this->assertNull($settings->transform(['has_default' => false]));
        $this->assertEquals(
            ['has_default' => true, 'options' => ['required' => false]],
            $settings->transform(['has_default' => true])
        );
        $this->assertEquals(
            ['has_default' => true, 'options' => ['required' => false]],
            $settings->transform(
                ['has_default' => 1, 'options' => ['required' => true]]
            )
        );
    }
}
