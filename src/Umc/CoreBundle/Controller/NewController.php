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

namespace App\Umc\CoreBundle\Controller;

use App\Umc\CoreBundle\Config\Loader\VersionAwareFactory;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Model\Form\Loader;
use App\Umc\CoreBundle\Model\Form\Processor\ProcessorInterface;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Model\Platform\Version;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;
    /**
     * @var VersionAwareFactory
     */
    private $formLoaderFactory;
    /**
     * @var Pool
     */
    private $platformPool;
    /**
     * @var ModifierInterface
     */
    private $uiModifier;

    /**
     * NewController constructor.
     * @param ParameterBagInterface $parameterBag
     * @param VersionAwareFactory $formLoaderFactory
     * @param Pool $platformPool
     * @param ModifierInterface $uiModifier
     */
    public function __construct(
        ParameterBagInterface $parameterBag,
        VersionAwareFactory $formLoaderFactory,
        Pool $platformPool,
        ModifierInterface $uiModifier
    ) {
        $this->parameterBag = $parameterBag;
        $this->formLoaderFactory = $formLoaderFactory;
        $this->platformPool = $platformPool;
        $this->uiModifier = $uiModifier;
    }

    /**
     * @Route("/new/:platfrom/:version", methods={"GET"})
     * @param string $platform
     * @param string $version
     * @return Response
     */
    public function run(string $platform, string $version = null): Response
    {
        try {
            $platformInstance = $this->platformPool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $uiConfig = $this->formLoaderFactory->create($versionInstance)->getConfig();
            $formConfig = $this->uiModifier->modify($uiConfig);
            $koConfig = [];
            foreach ($uiConfig as $type => $settings) {
                $koConfig[$type]['panel'] = $settings['panel'] ?? [];
                $koConfig[$type]['fields'] = array_keys($settings['fields'] ?? []);
                $koConfig[$type]['children'] = [];
                foreach ($settings['children'] ?? [] as $key => $value) {
                    $koConfig[$type]['children'][$key] = $value['className'];
                }
            }
            return $this->render(
                '@UmcCore/edit.html.twig',
                [
                    'formConfig' => $formConfig,
                    'platform' => $platformInstance,
                    'version' => $versionInstance,
                    'koConfig' => $koConfig,
                    'attribute_config' => $this->getAttributeConfig($versionInstance)
                ]
            );
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage() . $e->getTraceAsString());
            return new RedirectResponse($this->generateUrl('platform'));
        }
    }

    /**
     * @param Version $version
     * @return array
     */
    private function getAttributeConfig(Version $version): array
    {
        $key = $version->getAttributeConfigKey();
        if (!$key) {
            return [];
        }
        try {
            return $this->parameterBag->get($key);
        } catch (ParameterNotFoundException $e) {
            return [];
        }
    }
}
