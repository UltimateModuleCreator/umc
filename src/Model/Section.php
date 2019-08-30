<?php
/**
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 *  that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */
namespace App\Model;

class Section
{
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string[]
     */
    private $dependencies;

    /**
     * Section constructor.
     * @param string $label
     * @param string $code
     * @param string[] $dependencies
     */
    public function __construct(string $label, string $code, array $dependencies = [])
    {
        $this->label = $label;
        $this->code = $code;
        $this->dependencies = $dependencies;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }
}
