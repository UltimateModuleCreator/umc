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

use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Settings;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class SaveSettingsController extends AbstractController
{
    /**
     * @var Pool
     */
    private $pool;
    /**
     * @var Settings
     */
    private $repository;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SaveSettingsController constructor.
     * @param Pool $pool
     * @param Settings $repository
     * @param RequestStack $requestStack
     */
    public function __construct(Pool $pool, Settings $repository, RequestStack $requestStack)
    {
        $this->pool = $pool;
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/save-settings/{platform}/{version?}", methods={"POST"}, name="save-settings")
     * @param string $platform
     * @param string|null $version
     * @return JsonResponse
     */
    public function run(string $platform, ?string $version = null): JsonResponse
    {
        $response = [];
        try {
            $platformInstance = $this->pool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $data = $this->requestStack->getCurrentRequest()->get('settings');
            $isDelete = (bool)($data['restore']['restore'] ?? false);
            if ($version === null) {
                $this->repository->savePlatformConfig($platformInstance, $data);
                if (!$isDelete) {
                    $this->repository->savePlatformConfig($platformInstance, $data);
                } else {
                    $this->repository->deletePlatformConfig($platformInstance);
                }
            } else {
                if (!$isDelete) {
                    $this->repository->saveVersionConfig($versionInstance, $data);
                } else {
                    $this->repository->deleteVersionConfig($versionInstance);
                }
            }
            $response['success'] = true;
            $response['message'] = "Default settings were saved";
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        return new JsonResponse($response);
    }
}
