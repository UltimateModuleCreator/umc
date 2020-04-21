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
 */

declare(strict_types=1);

namespace App\Controller;

use App\Service\Form\Loader;
use App\Util\YamlLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class Edit extends AbstractController
{
    /**
     * @var string
     */
    private $template;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $basePath;
    /**
     * @var \App\Model\Settings
     */
    private $settingsModel;
    /**
     * @var Loader
     */
    private $formLoader;

    /**
     * Edit constructor.
     * @param string $template
     * @param RequestStack $requestStack
     * @param YamlLoader $yamlLoader
     * @param \App\Model\Settings $settingsModel
     * @param Loader $formLoader
     * @param string $basePath
     */
    public function __construct(
        string $template,
        RequestStack $requestStack,
        YamlLoader $yamlLoader,
        \App\Model\Settings $settingsModel,
        Loader $formLoader,
        string $basePath
    ) {
        $this->template = $template;
        $this->requestStack = $requestStack;
        $this->yamlLoader = $yamlLoader;
        $this->settingsModel = $settingsModel;
        $this->formLoader = $formLoader;
        $this->basePath = $basePath;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run(): Response
    {
        $moduleName = $this->requestStack->getCurrentRequest()->get('module');
        $defaults = $this->settingsModel->getSettingsSplit(true);
        if ($moduleName) {
            try {
                $data = $this->yamlLoader->load($this->basePath . basename($moduleName) . '.yml');
            } catch (\Exception $e) {
                $data = [];
            }
        } else {
            $data = [];
        }
        if (empty($data)) {
            $data = $defaults['module'] ?? [];
            $moduleName = 'New Module';
        }
        $formsConfig = $this->formLoader->getForms();
        $forms = [];
        foreach ($formsConfig as $key => $form) {
            if (isset($form['rows'])) {
                $forms[$key] = $form['rows'];
            }
        }
        return $this->render(
            $this->template,
            [
                'title' => $moduleName,
                'forms' => $forms,
                'data' => $data,
                'attribute_config' => $this->getGroupedAttributeConfig($formsConfig['attribute']['rows'] ?? []),
                'serialized_attribute_config' => $this->getGroupedAttributeConfig(
                    $formsConfig['serialized']['rows'] ?? []
                ),
                'uiConfig' => $this->getUiConfig($formsConfig),
                'defaults' => $defaults,
                'selectedMenu' => 'edit'
            ]
        );
    }

    /**
     * @param $forms
     * @return array
     */
    private function getUiConfig($forms): array
    {
        $uiConfig = [];
        foreach ($forms as $key => $form) {
            $uiConfig[$key] = [
                'fields' => array_reduce(
                    $form['rows'],
                    function ($all, $row) {
                        return array_merge(
                            $all,
                            array_keys($row)
                        );
                    },
                    []
                ),
                'panel' => $form['panel'] ?? '',
                'children' => $form['children'] ?? []

            ];
        }
        return $uiConfig;
    }

    /**
     * @param $rows
     * @return array
     */
    private function getGroupedAttributeConfig($rows): array
    {
        foreach ($rows as $row) {
            foreach ($row as $key => $field) {
                if ($key === 'type') {
                    return $field['options'] ?? [];
                }
            }
        }
        return [];
    }
}
