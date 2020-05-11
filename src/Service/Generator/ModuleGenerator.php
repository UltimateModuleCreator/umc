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

namespace App\Service\Generator;

use App\Model\Module;

class ModuleGenerator implements GeneratorInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Module constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Module $module
     * @param array $fileConfig
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function generateContent(Module $module, array $fileConfig): array
    {
        $fileConfig = $this->processFileConfig($fileConfig);

        $destination = $this->processDestination($fileConfig['destination'], $module);
        $content = $this->twig->render($fileConfig['template'], ['module' => $module]);
        if (!trim($content)) {
            return [];
        }
        return [
             $destination => str_replace('\n\r', PHP_EOL, $content)
        ];
    }

    /**
     * @param array $fileConfig
     * @return array
     */
    private function processFileConfig(array $fileConfig): array
    {
        if (!isset($fileConfig['source'])) {
            throw new \InvalidArgumentException("Missing source for file config " . print_r($fileConfig, true));
        }
        $fileConfig['template'] = $fileConfig['template'] ?? 'source/' . $fileConfig['source'] . '.html.twig';
        $fileConfig['destination'] = $fileConfig['destination'] ?? $fileConfig['source'];
        return $fileConfig;
    }

    /**
     * @param string $destination
     * @param Module $module
     * @return string
     */
    private function processDestination(string $destination, Module $module): string
    {
        return $destination;
    }
}
