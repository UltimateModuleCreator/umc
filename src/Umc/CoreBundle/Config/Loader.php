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

namespace App\Umc\CoreBundle\Config;

use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Loader
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var string
     */
    private $configClassName;
    /**
     * @var Provider[]
     */
    private $providers;
    /**
     * @var ModifierInterface
     */
    private $modifier;
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;
    /**
     * @var array
     */
    private $config;

    /**
     * Loader constructor.
     * @param ParameterBagInterface $parameterBag
     * @param ModifierInterface $modifier
     * @param ProcessorFactory $processorFactory
     * @param iterable $providers
     * @param string $configClassName
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        ModifierInterface $modifier,
        ProcessorFactory $processorFactory,
        iterable $providers,
        string $configClassName
    ) {
        $this->parameterBag = $parameterBag;
        $this->configClassName = $configClassName;
        $this->processorFactory = $processorFactory;
        $this->modifier = $modifier;
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * @param Provider $provider
     */
    private function addProvider(Provider $provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        if ($this->config === null) {
            $processor = $this->processorFactory->create();
            $config = $this->instantiateConfigClass();
            $configuration = $processor->processConfiguration(
                $config,
                array_map(
                    function (Provider $provider) {
                        return $provider->getConfig();
                    },
                    $this->providers
                )
            );
            $this->config = $this->modifier->modify($configuration);
        }
        return $this->config;
    }

    /**
     * @return ConfigurationInterface
     */
    private function instantiateConfigClass(): ConfigurationInterface
    {
        if (!is_subclass_of($this->configClassName, ConfigurationInterface::class, true)) {
            throw new \InvalidArgumentException(
                "$this->configClassName does not implement " . ConfigurationInterface::class
            );
        }
        $className = $this->configClassName;
        return new $className();
    }
}
