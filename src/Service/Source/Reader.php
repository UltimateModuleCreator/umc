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

declare(strict_types=1);

namespace App\Service\Source;

use App\Util\YamlLoader;

class Reader
{
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var array
     */
    private $files;
    /**
     * @var array
     */
    private $source;

    /**
     * Reader constructor.
     * @param YamlLoader $yamlLoader
     * @param array $files
     */
    public function __construct(YamlLoader $yamlLoader, array $files)
    {
        $this->yamlLoader = $yamlLoader;
        $this->files = $files;
    }

    /**
     * @throws \Exception
     */
    private function loadSource() : void
    {
        if ($this->source === null) {
            $this->source = [];
            foreach ($this->files as $file) {
                $this->source = array_merge($this->source, $this->yamlLoader->load($file));
            }
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getFiles() : array
    {
        $this->loadSource();
        return $this->source;
    }
}
