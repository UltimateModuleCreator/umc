<?php
/**
 *
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

namespace App\Controller;

use App\Service\Builder;
use App\Service\ModuleLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Save extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Builder
     */
    private $builder;
    /**
     * @var ModuleLoader
     */
    private $moduleLoader;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param Builder $builder
     * @param ModuleLoader $moduleLoader
     */
    public function __construct(
        RequestStack $requestStack,
        Builder $builder,
        ModuleLoader $moduleLoader
    ) {
        $this->requestStack = $requestStack;
        $this->builder = $builder;
        $this->moduleLoader = $moduleLoader;
    }

    /**
     * @return JsonResponse
     */
    public function run() : JsonResponse
    {
        try {
            $response = [];
            $data = $this->requestStack->getCurrentRequest()->get('data');
            $moduleData = $data['module'] ?? [];
            $entityData = $data['_entities'] ?? [];
            $moduleData['_entities'] = $entityData;
            $module = $this->moduleLoader->loadModule($moduleData);
            $this->builder->buildModule($module);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
            $response['link'] = $this->generateUrl('download', ['module' => $module->getExtensionName()]);
            $response['module'] = $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . print_r($e->getTraceAsString(), true) . '</pre>';
        }
        return new JsonResponse($response);
    }
}
