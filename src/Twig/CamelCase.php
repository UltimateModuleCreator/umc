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
namespace App\Twig;

use App\Util\StringUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CamelCase extends AbstractExtension
{
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * CamelCase constructor.
     * @param StringUtil $stringUtil
     */
    public function __construct(StringUtil $stringUtil)
    {
        $this->stringUtil = $stringUtil;
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters() : array
    {
        return [
            new TwigFilter('camel', [$this, 'camel']),
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    public function camel(string $string) : string
    {
        return $this->stringUtil->camel($string);
    }
}
