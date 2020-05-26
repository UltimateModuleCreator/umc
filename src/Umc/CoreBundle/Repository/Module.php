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

namespace App\Umc\CoreBundle\Repository;

use App\Umc\CoreBundle\Config\Provider;
use App\Umc\CoreBundle\Model\Platform\Version;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Module
{
    public const DESTINATION_PARAM_NAME = 'module.destination.root';
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var Provider\Factory
     */
    private $providerFactory;

    /**
     * Module constructor.
     * @param Filesystem $filesystem
     * @param ParameterBagInterface $parameterBag
     * @param Provider\Factory $providerFactory
     */
    public function __construct(
        Filesystem $filesystem,
        ParameterBagInterface $parameterBag,
        Provider\Factory $providerFactory
    ) {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
        $this->providerFactory = $providerFactory;
    }

    /**
     * @param \App\Umc\CoreBundle\Model\Module $module
     * @param Version $version
     */
    public function save(\App\Umc\CoreBundle\Model\Module $module, Version $version)
    {
        $destination = $this->parameterBag->get(self::DESTINATION_PARAM_NAME);
        $name = $module->getExtensionName();
        $platform = $version->getPlatform();
        $file = $this->getRoot($version) . '/' . $name . '.yml';
        $content = [
            'meta' => [
                'platform' => $platform->getCode(),
                'version' => $version->getCode()
            ],
            'module' => $module->toArray()
        ];
        $this->filesystem->dumpFile(
            $file,
            Yaml::dump($content, 100, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
        );
    }

    /**
     * @param string $name
     * @param Version $version
     * @return array
     * @throws Exception
     */
    public function load(string $name, Version $version)
    {
        $name = basename($name);
        $file = $this->getRoot($version) . '/' . $name . '.yml';
        if (!$this->filesystem->exists($file)) {
            //TODO: create separate exception type
            throw new Exception("Module {$name} was not created for .... ");
        }
        return $this->providerFactory->create($file)->getConfig();
    }

    /**
     * @param Version $version
     * @return string
     */
    public function getRoot(Version $version)
    {
        $source = $this->parameterBag->get(self::DESTINATION_PARAM_NAME);
        $platform = $version->getPlatform();
        return $source . '/' . $platform->getCode() . '/' . $version->getCode();
    }
}
