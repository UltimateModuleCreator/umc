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
use App\Service\ValidationException;
use App\Service\Validator;
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
     * @var Validator
     */
    private $validator;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param Builder $builder
     * @param ModuleLoader $moduleLoader
     * @param Validator $validator
     */
    public function __construct(
        RequestStack $requestStack,
        Builder $builder,
        ModuleLoader $moduleLoader,
        Validator $validator
    ) {
        $this->requestStack = $requestStack;
        $this->builder = $builder;
        $this->moduleLoader = $moduleLoader;
        $this->validator = $validator;
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
            $entityData = [];
            if (isset($data['entity'])) {
                foreach ($data['entity'] as $key => $props) {
                    $entityData[$key] = $props;
                    if (isset($props['is_name'])) {
                        $nameIndex = $props['is_name'];
                        $data['attribute'][$key][$nameIndex]['is_name'] = 1;
                        unset($entityData['is_name']);
                    }
                    $attributeData = [];
                    if (isset($data['attribute'][$key])) {
                        $attributeData = $data['attribute'][$key];
                    }
                    $entityData[$key]['_attributes'] = $attributeData;
                }
            }
            $moduleData['_entities'] = $entityData;
            $module = $this->moduleLoader->loadModule($moduleData);
            $errors = $this->validator->validate($module);
            if (count($errors)) {
                $response['validation'] = $errors;
                throw new ValidationException("Some fields are not valid");
            }
            $this->builder->buildModule($module);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage() . '<pre>' . print_r($e->getTraceAsString(), true);
        }
        return new JsonResponse($response);
    }
}
