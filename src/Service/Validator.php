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

namespace App\Service;

use App\Model\Attribute;
use App\Model\Entity;
use App\Model\Module;

class Validator
{
    private $restrictedNamespaces = ['magento'];

    private $restrictedEntityNames = [
        'resource', 'result', 'setup', 'attribute', 'options', 'system', 'data', 'collection', 'adminhtml',
    ];

    private $restrictedAttributeCodes = [
        'identities', 'namespace', 'default_values', 'created_at', 'updated_at'
    ];

    private $reservedKeywords = [
        '__halt_compiler', 'abstract', 'and', 'array',
        'as', 'break', 'callable', 'case',
        'catch', 'class', 'clone', 'const',
        'continue', 'declare', 'default', 'die',
        'do', 'echo', 'else', 'elseif',
        'empty', 'enddeclare', 'endfor', 'endforeach',
        'endif', 'endswitch', 'endwhile', 'eval',
        'exit', 'extends', 'final', 'for',
        'foreach', 'function', 'global', 'goto',
        'if', 'implements', 'include', 'include_once',
        'instanceof', 'insteadof', 'interface', 'isset',
        'list', 'namespace', 'new', 'or',
        'print', 'private', 'protected', 'public',
        'require', 'require_once', 'return', 'static',
        'switch', 'throw', 'trait', 'try',
        'unset', 'use', 'var', 'while',
        'xor'
    ];

    /**
     * @param Module $module
     * @return array
     */
    public function validate(Module $module) : array
    {
        $errors = [];
        if (in_array(strtolower($module->getData('namespace')), $this->restrictedNamespaces)) {
            $errors[] = $module->getData('namespace') . " cannot be used as a namespace";
        }
        $entityNames = [];
        foreach ($module->getEntities() as $key => $entity) {
            $errors = $errors + $this->validateEntity($entity);
            $nameSingular = strtolower($entity->getData('name_singular'));
            if (isset($entityNames[$nameSingular])) {
                $errors[] = $nameSingular . "is already used by another entity";
            }
            $entityNames[$nameSingular] = true;
        }
        return $errors;
    }

    /**
     * @param Entity $entity
     * @param $index
     * @return array
     */
    public function validateEntity(Entity $entity) : array
    {
        $errors = [];
        if (in_array(strtolower($entity->getData('name_singular')), $this->restrictedEntityNames) ||
            in_array(strtolower($entity->getData('name_singular')), $this->reservedKeywords)
        ) {
            $errors[] =
                $entity->getData('name_singular') . " cannot be used as an entity name";
        }
        if ($entity->getNameAttribute() === null) {
            $errors[] = "Entity {$entity->getData('name_singular')} must have an attribute that behaves as name";
        }
        foreach ($entity->getAttributes() as $attrIndex => $attribute) {
            $errors = array_merge($errors, $this->validateAttribute($attribute));
        }
        return $errors;
    }

    /**
     * @param Attribute $attribute
     * @return array
     */
    public function validateAttribute(Attribute $attribute) : array
    {
        $errors = [];
        if (in_array(strtolower($attribute->getData('code')), $this->restrictedAttributeCodes) ||
            in_array(strtolower($attribute->getData('code')), $this->reservedKeywords)
        ) {
            $errors[] =
                $attribute->getData('code') . " cannot be used as attribute code";
        }
        return $errors;
    }
}
