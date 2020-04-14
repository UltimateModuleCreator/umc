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

use App\Model\Module\Factory;
use App\Service\Builder;
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
     * @var Factory
     */
    private $moduleFactory;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param Builder $builder
     * @param Factory $moduleFactory
     */
    public function __construct(
        RequestStack $requestStack,
        Builder $builder,
        Factory $moduleFactory
    ) {
        $this->requestStack = $requestStack;
        $this->builder = $builder;
        $this->moduleFactory = $moduleFactory;
    }

    /**
     * @return JsonResponse
     */
    public function run() : JsonResponse
    {
        try {
            $response = [];
            $data = $this->requestStack->getCurrentRequest()->get('data');
            $module = $this->moduleFactory->create($data);
            $this->builder->buildModule($module);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
            $response['link'] = $this->generateUrl('download', ['module' => $module->getExtensionName()]);
            $response['module'] = $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . $e->getTraceAsString() . '</pre>';
        }
        return new JsonResponse($response);
    }
}
