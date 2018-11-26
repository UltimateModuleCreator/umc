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

use App\Form\Full;
use App\Util\YamlLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @param string $basePath
     */
    public function __construct(
        string $template,
        RequestStack $requestStack,
        YamlLoader $yamlLoader,
        string $basePath,
        array $attributeConfig
    ) {
        $this->template = $template;
        $this->requestStack = $requestStack;
        $this->yamlLoader = $yamlLoader;
        $this->basePath = $basePath;
        $this->attributeConfig = $attributeConfig;
    }

    /**
     * @return Response
     */
    public function run() : Response
    {
        $moduleName = $this->requestStack->getCurrentRequest()->get('module');
        $data = [];
        if ($moduleName) {
            try {
                $data = $this->yamlLoader->load($this->basePath . basename($moduleName) . '.yml');
            } catch (\Exception $e) {
                $data = [];
            }
        }
        $form = $this->createForm(Full::class, [], array(
            'action' => $this->generateUrl('save'),
            'method' => 'POST',
        ));
        if (isset($data['namespace']) || isset($data['module_name'])) {
            $title = 'Edit: ' . (($data['namespace']) ?? '') . '_' . (($data['module_name']) ?? '');
        } else {
            $title = 'New Module';
        }
        return $this->render(
            $this->template,
            [
                'form' => $form->createView(),
                'data' => $data,
                'title' => $title,
                'attribute_config' => $this->getAttributeConfig()
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
