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
namespace App\Service\License;

use App\Model\Module;

class Xml implements ProcessorInterface
{
    /**
     * @var Replacer
     */
    private $replacer;
    /**
     * @var array
     */
    private $cache = [];

    /**
     * Php constructor.
     * @param Replacer $replacer
     */
    public function __construct(Replacer $replacer)
    {
        $this->replacer = $replacer;
    }

    /**
     * @param Module $module
     * @return string
     */
    public function process(Module $module): string
    {
        $license = $this->replacer->replaceVars($module->getData('license'), $module);
        if (!trim($license)) {
            return '';
        }
        $key = md5($license);
        if (!isset($this->cache[$key])) {
            $eol = PHP_EOL;
            $license = trim($license);
            while (strpos($license, '*/') !== false) {
                $license = str_replace('*/', '', $license);
            }
            while (strpos($license, '/*') !== false) {
                $license = str_replace('/*', '', $license);
            }
            while (strpos($license, '<!--') !== false) {
                $license = str_replace('<!--', '', $license);
            }
            while (strpos($license, '-->') !== false) {
                $license = str_replace('-->', '', $license);
            }
            $lines = explode("\n", $license);
            $top = $eol . "<!--" . $eol;
            $footer = $eol . '-->' . $eol;
            $processed = $top . '/**' . $eol;
            foreach ($lines as $line) {
                $processed .= ' * ' . trim($line) . $eol;
            }
            $processed .= ' */' . $footer;
            $this->cache[$key] = $processed;
        }
        return $this->cache[$key];
    }
}
