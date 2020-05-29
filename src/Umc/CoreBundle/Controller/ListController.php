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
use App\Umc\CoreBundle\Repository\Module;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @var Pool
     */
    private $pool;
    /**
     * @var Module
     */
    private $repository;

    /**
     * ListController constructor.
     * @param Pool $pool
     * @param Module $repository
     */
    public function __construct(Pool $pool, Module $repository)
    {
        $this->pool = $pool;
        $this->repository = $repository;
    }

    /**
     * @Route("/list/{platform?}/{version?}", methods={"GET"}, name="list")
     * @param string|null $platform
     * @param string|null $version
     * @return Response
     */
    public function run($platform = null, $version = null): Response
    {
        $groups = [];
        if ($version !== null) {
            $platformInstance = $this->pool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $groups[] = [
                'platform' => $platformInstance,
                'versions' => [
                    [
                        'version' => $versionInstance,
                        'files' => $this->repository->getVersionModules($versionInstance)
                    ]
                ]
            ];
        } else {
            $platforms = ($platform === null) ? $this->pool->getPlatforms() : [$this->pool->getPlatform($platform)];
            foreach ($platforms as $platformInstance) {
                $versionsData = [];
                foreach ($platformInstance->getVersions() as $versionInstance) {
                    $versionsData[] = [
                        'version' => $versionInstance,
                        'files' => $this->repository->getVersionModules($versionInstance)
                    ];
                }
                $groups[] = [
                    'platform' => $platformInstance,
                    'versions' => $versionsData
                ];
            }
        }
        return $this->render('@UmcCore/list.html.twig', ['groups' => $groups]);
    }
}
