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

use App\Umc\CoreBundle\Config\Loader\PlatformAwareFactory;
use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;
use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use App\Umc\CoreBundle\Repository\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends AbstractController
{
    /**
     * @var PlatformAwareFactory
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
     * @var Module
     */
    private $moduleRepository;
    /**
     * @var Settings
     */
    private $settingsRepository;

    /**
     * EditController constructor.
     * @param PlatformAwareFactory $formLoaderFactory
     * @param Pool $platformPool
     * @param ModifierInterface $uiModifier
     * @param Module $moduleRepository
     * @param Settings $settingsRepository
     */
    public function __construct(
        PlatformAwareFactory $formLoaderFactory,
        Pool $platformPool,
        ModifierInterface $uiModifier,
        Module $moduleRepository,
        Settings $settingsRepository
    ) {
        $this->formLoaderFactory = $formLoaderFactory;
        $this->platformPool = $platformPool;
        $this->uiModifier = $uiModifier;
        $this->moduleRepository = $moduleRepository;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * @Route("/new/{platform}/{version?}", methods={"GET"}, name="new")
     * @Route("/edit/{platform}/{version}/{module}", methods={"GET"}, name="edit")
     * @param string $platform
     * @param string $version
     * @param string $module
     * @return Response
     */
    public function run(string $platform, ?string $version = null, $module = null): Response
    {
        try {
            $platformInstance = $this->platformPool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $uiConfig = $this->formLoaderFactory->createByVersion($versionInstance)->getConfig();
            if ($module !== null) {
                $data = $this->loadModuleData($platformInstance, $versionInstance, $module);
                $title = $data['module_name'];
            } else {
                $data = null;
                $title = 'New Module';
            }
            return $this->render(
                '@UmcCore/edit.html.twig',
                [
                    'formConfig' => $this->uiModifier->modify($uiConfig),
                    'platform' => $platformInstance,
                    'version' => $versionInstance,
                    'koConfig' => $this->getKoConfig($uiConfig),
                    'data' => $data,
                    'title' => $title,
                    'defaults' => $this->settingsRepository->loadVersionConfig($versionInstance)
                ]
            );
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage() . $e->getTraceAsString());
            return new RedirectResponse($this->generateUrl('index'));
        }
    }

    /**
     * @param Platform $platform
     * @param Platform\Version $version
     * @param $module
     * @return array
     * @throws \Exception
     */
    private function loadModuleData(Platform $platform, Platform\Version $version, $module)
    {
        return $this->moduleRepository->load($module, $version)['module'];
    }

    /**
     * @param array $config
     * @return array
     */
    private function getKoConfig(array $config)
    {
        $koConfig = [];
        foreach ($config as $type => $settings) {
            $koConfig[$type]['panel'] = $settings['panel'] ?? [];
            $koConfig[$type]['fields'] = array_keys($settings['fields'] ?? []);
            $koConfig[$type]['children'] = $settings['children'] ?? [];
        }
        return $koConfig;
    }
}
