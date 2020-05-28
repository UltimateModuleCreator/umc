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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * PlatformController constructor.
     * @param Pool $pool
     */
    public function __construct(Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @Route("/platform/{platform}", methods={"GET"}, name="platform")
     * @param string $platform
     * @return Response
     */
    public function run(string $platform)
    {
        try {
            $instance = $this->pool->getPlatform($platform);
            return $this->render('@UmcCore/platform.html.twig', ['platform' => $instance]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return new RedirectResponse($this->generateUrl('index'));
        }
    }
}
