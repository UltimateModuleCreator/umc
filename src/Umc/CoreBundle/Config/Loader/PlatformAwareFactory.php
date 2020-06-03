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

namespace App\Umc\CoreBundle\Config\Loader;

use App\Umc\CoreBundle\Config\Loader;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Config\ProcessorFactory;
use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Version;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PlatformAwareFactory
{
    /**
     * @var Factory
     */
    private $providerFactory;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var ModifierInterface
     */
    private $modifier;
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;
    /**
     * @var string
     */
    private $configKey;
    /**
     * @var string
     */
    private $className;

    /**
     * VersionAwareFactory constructor.
     * @param Factory $providerFactory
     * @param ParameterBagInterface $parameterBag
     * @param ModifierInterface $modifier
     * @param ProcessorFactory $processorFactory
     * @param string $configKey
     * @param string $className
     */
    public function __construct(
        Factory $providerFactory,
        ParameterBagInterface $parameterBag,
        ModifierInterface $modifier,
        ProcessorFactory $processorFactory,
        string $configKey,
        string $className
    ) {
        $this->providerFactory = $providerFactory;
        $this->parameterBag = $parameterBag;
        $this->modifier = $modifier;
        $this->processorFactory = $processorFactory;
        $this->configKey = $configKey;
        $this->className = $className;
    }

    /**
     * @param Version $version
     * @param bool $usePlatform
     * @return Loader
     */
    public function createByVersion(Version $version, $usePlatform = true): Loader
    {
        $files = $version->getConfig($this->configKey, $usePlatform);
        return $this->create($files);
    }

    public function createByPlatform(Platform $platform): Loader
    {
        $files = $platform->getConfig($this->configKey);
        return $this->create($files);
    }

    /**
     * @param $files
     * @return Loader
     */
    private function create($files): Loader
    {
        $providers = array_map(
            function ($file) {
                return $this->providerFactory->create($file);
            },
            $files
        );
        return new Loader(
            $this->parameterBag,
            $this->modifier,
            $this->processorFactory,
            $providers,
            $this->className
        );
    }
}
