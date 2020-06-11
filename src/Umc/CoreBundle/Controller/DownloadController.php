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
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadController extends AbstractController
{
    /**
     * @var Module
     */
    private $repository;
    /**
     * @var Pool
     */
    private $pool;

    /**
     * DownloadController constructor.
     * @param Module $repository
     * @param Pool $pool
     */
    public function __construct(Module $repository, Pool $pool)
    {
        $this->repository = $repository;
        $this->pool = $pool;
    }

    /**
     * @param string $platform
     * @param string $version
     * @param string $module
     * @return BinaryFileResponse|RedirectResponse|Response
     * @Route("/download/{platform}/{version}/{module}", methods={"GET"}, name="download")
     */
    public function run(string $platform, string $version, string $module): Response
    {
        try {
            $platformInstance = $this->pool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $this->repository->load($module, $versionInstance);
            $filename = $this->repository->getRoot($versionInstance) . '/' . $module . '.zip';
            return $this->file($filename);
        } catch (\Exception $e) {
            $this->addFlash('danger', 'The requested module does not exist');
            return $this->redirectToRoute('index');
        }
    }
}
