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

namespace App\Service\Generator;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Module;
use App\Util\StringUtil;

class AttributeGenerator implements GeneratorInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * EntityGenerator constructor.
     * @param \Twig_Environment $twig
     * @param StringUtil $stringUtil
     */
    public function __construct(\Twig_Environment $twig, StringUtil $stringUtil)
    {
        $this->twig = $twig;
        $this->stringUtil = $stringUtil;
    }


    /**
     * @param Module $module
     * @param array $fileConfig
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function generateContent(Module $module, array $fileConfig) : array
    {
        $result = [];
        foreach ($module->getEntities() as $entity) {
            foreach ($entity->getAttributes() as $attribute) {
                $destination = $this->processDestination($fileConfig['destination'], $attribute);
                $content = $this->twig->render(
                    $fileConfig['template'],
                    [
                        'attribute' => $attribute,
                        'entity' => $entity,
                        'module' => $module
                    ]
                );
                if (trim($content)) {
                    if (isset($fileConfig['is_image']) && $fileConfig['is_image']) {
                        $content = @base64_decode(trim($content));
                        if ($content) {
                            $result[$destination] = $content;
                        }
                    } else {
                        $result[$destination] = str_replace("\n\r", PHP_EOL, $content);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $destination
     * @param Attribute $attribute
     * @return string
     */
    private function processDestination(string $destination, Attribute $attribute) : string
    {
        $entity = $attribute->getEntity();
        $replace = [
            '_Entity_' =>  ucfirst($entity->getNameSingular()),
            '_Code_' => ucfirst($this->stringUtil->camel($attribute->getCode())),
            '_entity_' => $this->stringUtil->snake($entity->getNameSingular()),
            '_code_' => $this->stringUtil->snake($attribute->getCode())
        ];
        return str_replace(array_keys($replace), array_values($replace), $destination);
    }
}
