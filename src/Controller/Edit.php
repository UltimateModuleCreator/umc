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
     * @var array
     */
    private $attributeConfig;
    /**
     * @var \App\Model\Settings
     */
    private $settingsModel;
    /**
     * @var array
     */
    private $formConfig;
    /**
     * @var array
     */
    private $configProps = [
        'can_be_name',
        'can_show_in_grid',
        'can_have_options',
        'can_be_required'
    ];

    /**
     * Edit constructor.
     * @param string $template
     * @param RequestStack $requestStack
     * @param YamlLoader $yamlLoader
     * @param \App\Model\Settings $settingsModel
     * @param string $basePath
     * @param array $attributeConfig
     * @param array $formConfig
     */
    public function __construct(
        string $template,
        RequestStack $requestStack,
        YamlLoader $yamlLoader,
        \App\Model\Settings $settingsModel,
        string $basePath,
        array $attributeConfig,
        array $formConfig
    ) {
        $this->template = $template;
        $this->requestStack = $requestStack;
        $this->yamlLoader = $yamlLoader;
        $this->settingsModel = $settingsModel;
        $this->basePath = $basePath;
        $this->attributeConfig = $attributeConfig;
        $this->formConfig = $formConfig;
    }

    /**
     * @return Response
     */
    public function run() : Response
    {
        $moduleName = $this->requestStack->getCurrentRequest()->get('module');
        $defaults = $this->settingsModel->getSettings(true);
        if ($moduleName) {
            try {
                $data = $this->yamlLoader->load($this->basePath . basename($moduleName) . '.yml');
                $entities = $data['_entities'] ?? [];
                unset($data['_entities']);
                $data = [
                    'module' => $data,
                    '_entities' => $entities
                ];
            } catch (\Exception $e) {
                $data = [
                    'module' => $defaults['module'] ?? []
                ];
            }
        } else {
            $data = [
                'module' => $defaults['module'] ?? []
            ];
        }
        $forms = [];
        foreach ($this->formConfig as $key => $config) {
            $forms[$key] = $this->createForm($config['class'], [], ['attr' => ['name' => $config['name']]])
                ->createView();
        }
        $isNewMode = true;
        if (isset($data['module']['namespace']) && isset($data['module']['module_name'])) {
            $title = 'Edit: ' . (($data['module']['namespace']) ?? '') . '_' . (($data['module']['module_name']) ?? '');
            $isNewMode = false;
        } else {
            $title = 'New Module';
        }
        return $this->render(
            $this->template,
            [
                'forms' => $forms,
                'data' => $data,
                'title' => $title,
                'attribute_config' => $this->getAttributeConfig(),
                'defaults' => $defaults,
                'is_new_mode' => (int)$isNewMode,
                'submit_action' => $this->generateUrl('save')
            ]
        );
    }

    /**
     * @return false|string
     */
    private function getAttributeConfig() : string
    {
        $config = [];
        foreach ($this->attributeConfig as $type => $settings) {
            $attrConfig = [];
            foreach ($this->configProps as $prop) {
                $attrConfig[$prop] = $settings[$prop];
            }
            $config[$type] = $attrConfig;
        }
        return json_encode($config);
    }
}
