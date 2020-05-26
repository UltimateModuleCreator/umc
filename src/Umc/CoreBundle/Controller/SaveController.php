<?php
declare(strict_types=1);

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

namespace App\Umc\CoreBundle\Controller;

use App\Umc\CoreBundle\Model\Module\Factory\Locator;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use App\Umc\CoreBundle\Service\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class SaveController extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Pool
     */
    private $platformPool;
    /**
     * @var Module
     */
    private $repository;
    /**
     * @var Builder
     */
    private $builder;

    /**
     * SaveController constructor.
     * @param RequestStack $requestStack
     * @param Pool $platformPool
     * @param Module $repository
     * @param Builder $builder
     */
    public function __construct(RequestStack $requestStack, Pool $platformPool, Module $repository, Builder $builder)
    {
        $this->requestStack = $requestStack;
        $this->platformPool = $platformPool;
        $this->repository = $repository;
        $this->builder = $builder;
    }

    /**
     * @Route("/save/{platform}/{version?}", methods={"POST"}, name="save")
     * @param string $platform
     * @param string|null $version
     * @return JsonResponse
     */
    public function run(string $platform, ?string $version = null): JsonResponse
    {
        try {
            $platformInstance = $this->platformPool->getPlatform($platform);
            $versionInstance = $platformInstance->getVersion($version);
            $data = $this->requestStack->getCurrentRequest()->get('data');
            $module = $this->builder->build($versionInstance, $data);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
            $response['module'] = $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        return new JsonResponse($response);
    }
}
