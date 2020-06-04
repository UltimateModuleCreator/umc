<?php
/**
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

namespace App\Umc\CoreBundle\Service\License;

use App\Umc\CoreBundle\Model\Module;

class Processor
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var string
     */
    private $suffix;
    /**
     * @var string
     */
    private $default;
    /**
     * @var string[]
     */
    private $cache = [];

    /**
     * Processor constructor.
     * @param string $code
     * @param string $prefix
     * @param string $suffix
     * @param string $default
     */
    public function __construct(string $code, string $prefix, string $suffix, string $default = '')
    {
        $this->code = $code;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
        $this->default = $default;
    }

    /**
     * @param string $license
     * @param Module $module
     * @return string
     */
    private function replaceVars(string $license, Module $module): string
    {
        $replace = [
            '{{Namespace}}' => $module->getNamespace(),
            '{{Module}}' => $module->getModuleName(),
            '{{Y}}' => date('Y')
        ];
        return str_replace(array_keys($replace), array_values($replace), $license);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param Module $module
     * @return string
     */
    public function process(Module $module): string
    {
        $license = $this->replaceVars($module->getLicense(), $module);
        if (!trim($license)) {
            return $this->default;
        }
        $key = md5($license);
        if (!isset($this->cache[$key])) {
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
            $processed = $this->prefix . '/**' . PHP_EOL;
            foreach ($lines as $line) {
                $processed .= rtrim(' * ' . trim($line)) . PHP_EOL;
            }
            $processed .= ' */' . $this->suffix;
            $this->cache[$key] = $processed;
        }
        return $this->cache[$key];
    }
}
