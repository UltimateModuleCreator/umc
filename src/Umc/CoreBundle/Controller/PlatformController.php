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

use App\Umc\CoreBundle\Model\Platform;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlatformController extends AbstractController
{
    /**
     * @var Platform\Pool
     */
    private $pool;

    /**
     * PlatformController constructor.
     * @param Platform\Pool $pool
     */
    public function __construct(Platform\Pool $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @Route("/platform", methods={"GET"})
     * @return Response
     */
    public function run(): Response
    {
        return $this->render('@UmcCore/platform.html.twig', ['platforms' => $this->pool->getPlatforms()]);
    }
}
