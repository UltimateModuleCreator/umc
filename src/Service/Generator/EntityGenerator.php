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

use App\Model\Entity;
use App\Model\Module;
use App\Util\StringUtil;

class EntityGenerator implements GeneratorInterface
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
            $destination = $this->processDestination($fileConfig['destination'], $entity);
            $content = $this->twig->render($fileConfig['template'], ['entity' => $entity, 'module' => $module]);
            if (trim($content)) {
                $result[$destination] = str_replace("\n\r", PHP_EOL, $content);
            }
        }
        return $result;
    }

    /**
     * @param string $destination
     * @param Entity $entity
     * @return string
     */
    private function processDestination(string $destination, Entity $entity) : string
    {
        $replace = [
            '_Entity_' =>  ucfirst($this->stringUtil->camel($entity->getNameSingular())),
            '_entity_' =>  strtolower($this->stringUtil->camel((string)$entity->getNameSingular())),
            '_namespace_' => $this->stringUtil->snake((string)$entity->getModule()->getNamespace()),
            '_modulename_' => strtolower($this->stringUtil->camel(
                (string)$entity->getModule()->getModuleName()
            ))
        ];
        return str_replace(array_keys($replace), array_values($replace), $destination);
    }
}
