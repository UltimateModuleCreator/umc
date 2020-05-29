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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * SaveSettingsController constructor.
     * @param Pool $pool
     * @param RequestStack $requestStack
     */
    public function __construct(Pool $pool, RequestStack $requestStack)
    {
        $this->pool = $pool;
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
        $data = $this->requestStack->getCurrentRequest()->get('settings');
        var_dump($data);exit;
    }
}
