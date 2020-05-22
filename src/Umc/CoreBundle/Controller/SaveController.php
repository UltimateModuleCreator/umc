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

use App\Model\Module\Factory;
use App\Service\Builder;
use App\Umc\CoreBundle\Model\Module\Factory\Locator;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Repository\Module;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

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
     * @var Locator
     */
    private $moduleFactoryLocator;
    /**
     * @var Builder
     */
    private $builder;

    /**
     * SaveController constructor.
     * @param RequestStack $requestStack
     * @param Pool $platformPool
     * @param Module $repository
     * @param Locator $moduleFactoryLocator
     * @param Builder $builder
     */
    public function __construct(RequestStack $requestStack, Pool $platformPool, Module $repository, Locator $moduleFactoryLocator, Builder $builder)
    {
        $this->requestStack = $requestStack;
        $this->platformPool = $platformPool;
        $this->repository = $repository;
        $this->moduleFactoryLocator = $moduleFactoryLocator;
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
//            $response = [];
            $data = $this->requestStack->getCurrentRequest()->get('data');
//            $response = $data;
//
//            $content = [
//                'meta' => [
//                    'platform' => $platformInstance->getCode(),
//                    'version' => $versionInstance->getCode()
//                ],
//                'module' => $data
//            ];
//            $this->repository->save('dummy', $data, $platformInstance, $versionInstance);
//            $response = [
//                'success' => true,
//                'module' => $data,
//            ];
            $factory = $this->moduleFactoryLocator->getFactory($versionInstance);
            $module = $factory->create($data);
//            echo "<pre>"; print_r($module->toArray());exit;
            $this->builder->buildModule($module);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
//            $response['link'] = $this->generateUrl('download', ['module' => $module->getExtensionName()]);
            $response['module'] = $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        return new JsonResponse($response);
    }
}
