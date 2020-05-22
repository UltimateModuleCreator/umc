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
use App\Umc\CoreBundle\Model\Platform;
use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\ParameterBag;
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
     * @param string $name
     * @param array $data
     * @param Platform $platform
     * @param Platform\Version $version
     */
    public function save(string $name, array $data, Platform $platform, Platform\Version $version)
    {
        $destination = $this->parameterBag->get(self::DESTINATION_PARAM_NAME);
        $name = basename($name);
        $file = $platform->getCode() . '/' . $version->getCode() . '/' . $name . '.yml';
        $content = [
            'meta' => [
                'platform' => $platform->getCode(),
                'version' => $version->getCode()
            ],
            'module' => $data
        ];
        $this->filesystem->dumpFile(
            rtrim($destination, '/') . '/' . $file,
            Yaml::dump($content, 100, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE)
        );
    }

    /**
     * @param string $name
     * @param Platform $platform
     * @param Platform\Version $version
     * @return array
     * @throws Exception
     */
    public function load(string $name, Platform $platform, Platform\Version $version)
    {
        $name = basename($name);
        $source = $this->parameterBag->get(self::DESTINATION_PARAM_NAME);
        $file = $source . '/' . $platform->getCode() . '/' . $version->getCode() . '/' . $name . '.yml';
        if (!$this->filesystem->exists($file)) {
            //TODO: create separate exception type
            throw new Exception("Module {$name} was not created for .... ");
        }
        return $this->providerFactory->create($file)->getConfig();
    }
}
