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
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
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
     * @var Settings
     */
    private $repository;

    /**
     * SettingsController constructor.
     * @param PlatformAwareFactory $formLoaderFactory
     * @param Pool $platformPool
     * @param Settings $repository
     */
    public function __construct(PlatformAwareFactory $formLoaderFactory, Pool $platformPool, Settings $repository)
    {
        $this->formLoaderFactory = $formLoaderFactory;
        $this->platformPool = $platformPool;
        $this->repository = $repository;
    }

    /**
     * @param $platform
     * @param null $version
     * @return Response
     * @Route("/settings/{platform}/{version?}", methods={"GET"}, name="settings")
     */
    public function run($platform, $version = null): Response
    {
        try {
            $platformInstance = $this->platformPool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $config = ($version === null)
                ? $this->formLoaderFactory->createByPlatform($platformInstance)->getConfig()
                : $this->formLoaderFactory->createByVersion($versionInstance)->getConfig();
            $fields = [];
            foreach ($config as $type => $settings) {
                $fields[$type] = array_keys($settings['fields']);
            }
            $fields['restore'] = ['restore'];
            $title = "Default Settings for {$platformInstance->getName()}";
            if ($version !== null) {
                $title .= " version {$versionInstance->getLabel()}";
            }
            $restore = [
                'label' => ($version !== null)
                    ? 'Use platform default settings'
                    : "Use factory settings",
                'name' => 'restore.restore'
            ];
            if ($version !== null) {
                try {
                    $values = $this->repository->loadVersionConfig($versionInstance, false);
                    $values['restore']['restore'] = false;
                } catch (Settings\MissingSettingsFileException $e) {
                    $values = $this->repository->loadPlatformConfig($platformInstance);
                    $values['restore']['restore'] = true;
                }
            } else {
                $values = $this->repository->loadPlatformConfig($platformInstance);
                $values['restore']['restore'] = ($values === []);
            }
            return $this->render(
                '@UmcCore/settings.html.twig',
                [
                    'platform' => $platformInstance,
                    'version' => $versionInstance,
                    'config' => $config,
                    'fields' => $fields,
                    'saveUrl' => $this->generateUrl('save-settings', ['platform' => $platform, 'version' => $version]),
                    'values' => $values,
                    'title' => $title,
                    'restore' => $restore
                ]
            );
        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('index');
        }
    }
}
