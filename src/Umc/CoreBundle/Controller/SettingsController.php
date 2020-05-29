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
use App\Umc\CoreBundle\Model\Platform\Pool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    /**
     * @var VersionAwareFactory
     */
    private $formLoaderFactory;
    /**
     * @var Pool
     */
    private $platformPool;

    /**
     * SettingsController constructor.
     * @param VersionAwareFactory $formLoaderFactory
     * @param Pool $platformPool
     */
    public function __construct(VersionAwareFactory $formLoaderFactory, Pool $platformPool)
    {
        $this->formLoaderFactory = $formLoaderFactory;
        $this->platformPool = $platformPool;
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
            //TODO: handle for platform only
            $platformInstance = $this->platformPool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $config = $this->formLoaderFactory->create($versionInstance)->getConfig();
            $fields = [];
            foreach ($config as $type => $settings) {
                $fields[$type] = array_keys($settings['fields']);
            }
            //TODO:read existing settings field to get the values
            return $this->render(
                '@UmcCore/settings.html.twig',
                [
                    'platform' => $platformInstance,
                    'version' => $versionInstance,
                    'config' => $config,
                    'fields' => $fields,
                    'saveUrl' => $this->generateUrl('save-settings', ['platform' => $platform, 'version' => $version]),
                    'values' => []
                ]
            );
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('index');
        }
    }
}
