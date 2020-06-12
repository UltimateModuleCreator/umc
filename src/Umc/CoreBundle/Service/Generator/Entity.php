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
 *
 */

declare(strict_types=1);

namespace App\Umc\CoreBundle\Service\Generator;

use App\Umc\CoreBundle\Model\Module;
use App\Umc\CoreBundle\Util\StringUtil;
use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Entity implements GeneratorInterface
{
    /**
     * @var Twig
     */
    private $twig;
    /**
     * @var StringUtil
     */
    private $stringUtil;
    /**
     * @var ContentProcessor
     */
    private $processor;

    /**
     * Entity constructor.
     * @param Twig $twig
     * @param StringUtil $stringUtil
     * @param ContentProcessor $processor
     */
    public function __construct(Twig $twig, StringUtil $stringUtil, ContentProcessor $processor)
    {
        $this->twig = $twig;
        $this->stringUtil = $stringUtil;
        $this->processor = $processor;
    }

    /**
     * @param Module $module
     * @param array $fileConfig
     * @return array
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function generateContent(Module $module, array $fileConfig): array
    {
        if (!isset($fileConfig['source'])) {
            throw new \InvalidArgumentException("Missing source for file config " . print_r($fileConfig, true));
        }
        if (!isset($fileConfig['destination'])) {
            throw new \InvalidArgumentException("Missing source for file config " . print_r($fileConfig, true));
        }
        $result = [];
        foreach ($module->getEntities() as $entity) {
            $destination = $this->processDestination($fileConfig['destination'], $entity);
            $content = $this->twig->render($fileConfig['source'], ['entity' => $entity, 'module' => $module]);
            if (trim($content)) {
                $result[$destination] = $this->processor->process($content);
            }
        }
        return $result;
    }

    /**
     * @param string $destination
     * @param \App\Umc\CoreBundle\Model\Entity $entity
     * @return string
     */
    private function processDestination(string $destination, \App\Umc\CoreBundle\Model\Entity $entity): string
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
