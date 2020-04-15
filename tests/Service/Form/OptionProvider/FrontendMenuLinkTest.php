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

namespace App\Tests\Service\Form\OptionProvider;

use App\Service\Form\OptionProvider\FrontendMenuLink;
use PHPUnit\Framework\TestCase;

class FrontendMenuLinkTest extends TestCase
{
    /**
     * @covers \App\Service\Form\OptionProvider\FrontendMenuLink::getOptions
     */
    public function testGetOptions()
    {
        $provider = new FrontendMenuLink();
        $this->assertEquals(3, count($provider->getOptions()));
    }
}
