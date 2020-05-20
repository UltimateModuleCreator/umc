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
use App\Umc\CoreBundle\Config\Provider\Factory;
use App\Umc\CoreBundle\Model\Platform\Version;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class VersionAwareFactory
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
     * @var string
     */
    private $configKey;
    /**
     * @var string
     */
    private $className;
    /**
     * @var bool
     */
    private $usePlatform;

    /**
     * VersionAwareFactory constructor.
     * @param Factory $providerFactory
     * @param ParameterBagInterface $parameterBag
     * @param ModifierInterface $modifier
     * @param string $configKey
     * @param string $className
     * @param bool $usePlatform
     */
    public function __construct(
        Factory $providerFactory,
        ParameterBagInterface $parameterBag,
        ModifierInterface $modifier,
        string $configKey,
        string $className,
        bool $usePlatform = true
    ) {
        $this->providerFactory = $providerFactory;
        $this->parameterBag = $parameterBag;
        $this->modifier = $modifier;
        $this->configKey = $configKey;
        $this->className = $className;
        $this->usePlatform = $usePlatform;
    }

    /**
     * @param Version $version
     * @return Loader
     */
    public function create(Version $version)
    {
        $files = $version->getConfig($this->configKey, $this->usePlatform);
        $providers = array_map(
            function ($file) {
                return $this->providerFactory->create($file);
            },
            $files
        );
        return new Loader($this->parameterBag, $this->modifier, $providers, $this->className);
    }
}
