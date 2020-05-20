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

namespace App\Umc\CoreBundle\Model\Form;

use App\Umc\CoreBundle\Model\Config\ProviderFactory;
use App\Umc\CoreBundle\Model\Config\ProviderInterface;
use App\Umc\CoreBundle\Model\Form\Processor\ProcessorInterface;
use App\Umc\CoreBundle\Model\Platform;
use Symfony\Component\Config\Definition\Processor;

/**
 * @deprecated
 */
class Loader
{
    /**
     * @var array
     */
    private $configData;
    /**
     * @var ProviderFactory
     */
    private $providerFactory;
    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * Loader constructor.
     * @param ProviderFactory $providerFactory
     * @param ProcessorInterface $processor
     */
    public function __construct(
        ProviderFactory $providerFactory,
        ProcessorInterface $processor
    ) {
        $this->providerFactory = $providerFactory;
        $this->processor = $processor;
    }

    /**
     * @param Platform $platform
     * @param Platform\Version $version
     * @return array
     */
    public function getConfig(Platform $platform, Platform\Version $version): array
    {
        $platformCode = $platform->getCode();
        $versionCode = $version->getCode();
        if (!isset($this->configData[$platformCode][$versionCode])) {
            $processor = new Processor();
            $providers = [];
            $files = array_merge($platform->getFormConfig(), $version->getFormConfig());
            foreach ($files as $file) {
                $providers[] = $this->providerFactory->create($file);
            }
            $config = new Config();
            $configData = $processor->processConfiguration(
                $config,
                array_map(
                    function (ProviderInterface $provider) {
                        return $provider->getConfig();
                    },
                    $providers
                )
            );
            $this->configData[$platformCode][$versionCode] = $this->processor->process($configData);
        }
        return $this->configData[$platformCode][$versionCode];
    }
}
