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

namespace App\Umc\CoreBundle\Service\Generator;

use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Module implements GeneratorInterface
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * Module constructor.
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param \App\Umc\CoreBundle\Model\Module $module
     * @param array $fileConfig
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generateContent(\App\Umc\CoreBundle\Model\Module $module, array $fileConfig): array
    {
        if (!isset($fileConfig['source'])) {
            throw new \InvalidArgumentException("Missing source for file config " . print_r($fileConfig, true));
        }
        if (!isset($fileConfig['destination'])) {
            throw new \InvalidArgumentException("Missing source for file config " . print_r($fileConfig, true));
        }
        $content = $this->twig->render($fileConfig['source'], ['module' => $module]);
        if (!trim($content)) {
            return [];
        }
        return [
            $fileConfig['destination'] => str_replace('\n\r', PHP_EOL, $content)
        ];
    }
}
