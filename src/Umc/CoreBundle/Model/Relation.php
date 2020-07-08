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

namespace App\Umc\CoreBundle\Model;

use App\Umc\CoreBundle\Util\StringUtil;

class Relation
{
    public const TYPE_ONE_TO_MANY = 'parent';
    public const TYPE_MANY_TO_MANY = 'sibling';
    /**
     * @var Module
     */
    private $module;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $entityOne;
    /**
     * @var string
     */
    private $entityTwo;
    /**
     * @var string
     */
    private $entityOneLabel;
    /**
     * @var string
     */
    private $entityTwoLabel;
    /**
     * @var string
     */
    private $code;
    /**
     * @var bool
     */
    private $entityOneFrontend;
    /**
     * @var bool
     */
    private $entityTwoFrontend;
    /**
     * @var string
     */
    private $type;
    /**
     * @var Entity
     */
    private $entityOneInstance;
    /**
     * @var Entity
     */
    private $entityTwoInstance;
    /**
     * @var StringUtil
     */
    private $stringUtil;

    /**
     * Relation constructor.
     * @param Module $module
     * @param StringUtil $stringUtil
     * @param array $data
     */
    public function __construct(Module $module, StringUtil $stringUtil, array $data)
    {
        $this->module = $module;
        $this->stringUtil = $stringUtil;
        $this->label = (string)($data['label'] ?? '');
        $this->entityOne = (string)($data['entity_one'] ?? '');
        $this->entityTwo = (string)($data['entity_two'] ?? '');
        $this->type = (string)($data['type'] ?? '');
        $this->code = (string)($data['code'] ?? '');
        $this->entityOneLabel = (string)($data['entity_one_label']);
        $this->entityTwoLabel = (string)($data['entity_two_label']);
        $this->entityOneFrontend = (bool)($data['entity_one_frontend'] ?? false);
        $this->entityTwoFrontend = (bool)($data['entity_two_frontend'] ?? false);
    }

    /**
     * @param Entity $entity
     * @return Entity
     */
    public function getRelatedEntity(Entity $entity): Entity
    {
        if ($this->getEntityTwoInstance()->getNameSingular() === $entity->getNameSingular()) {
            return $this->getEntityOneInstance();
        }
        if ($this->getEntityOneInstance()->getNameSingular() === $entity->getNameSingular()) {
            return $this->getEntityTwoInstance();
        }
        throw new \InvalidArgumentException(
            "Entity with name singular {$entity->getNameSingular()} does not exist is not in this relation"
        );
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    public function getRelatedEntityFrontend(Entity $entity): bool
    {
        if ($this->getEntityTwoInstance()->getNameSingular() === $entity->getNameSingular()) {
            return $this->isEntityOneFrontend();
        }
        if ($this->getEntityOneInstance()->getNameSingular() === $entity->getNameSingular()) {
            return $this->isEntityTwoFrontend();
        }
        throw new \InvalidArgumentException(
            "Entity with name singular {$entity->getNameSingular()} does not exist is not in this relation"
        );
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public function getRelatedEntityPk(Entity $entity)
    {
        return $this->stringUtil->snake($this->getCode() . $this->getRelatedEntity($entity)->getPk());
    }

    /**
     * @param Entity $entity
     * @return string
     */
    public function getRelatedEntityName(Entity $entity)
    {
        return $this->stringUtil->snake($this->getCode() . $this->getRelatedEntity($entity)->getNameSingular());
    }

    /**
     * @param string $code
     * @return Entity
     */
    private function getEntity(string $code): Entity
    {
        foreach ($this->module->getEntities() as $entity) {
            if ($entity->getNameSingular() === $code) {
                return $entity;
            }
        }
        throw new \InvalidArgumentException("Entity with name singular {$code} does not exist");
    }

    /**
     * @return Entity
     */
    public function getEntityOneInstance(): Entity
    {
        if ($this->entityOneInstance === null) {
            $this->entityOneInstance = $this->getEntity($this->entityOne);
        }
        return $this->entityOneInstance;
    }

    /**
     * @return Entity
     */
    public function getEntityTwoInstance(): Entity
    {
        if ($this->entityTwoInstance === null) {
            $this->entityTwoInstance = $this->getEntity($this->entityTwo);
        }
        return $this->entityTwoInstance;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getEntityOne(): string
    {
        return $this->entityOne;
    }

    /**
     * @return string
     */
    public function getEntityTwo(): string
    {
        return $this->entityTwo;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'entity_one' => $this->entityOne,
            'entity_two' => $this->entityTwo,
            'type' => $this->type,
            'code' => $this->code,
            'entity_one_label' => $this->entityOneLabel,
            'entity_two_label' => $this->entityTwoLabel,
            'entity_one_frontend' => $this->entityOneFrontend,
            'entity_two_frontend' => $this->entityTwoFrontend
        ];
    }

    /**
     * @param $entity
     * @return string
     */
    public function getRelatedEntityLabel($entity): string
    {
        if ($this->getEntityTwoInstance() === $entity) {
            return $this->getEntityOneLabel();
        }
        if ($this->getEntityOneInstance() === $entity) {
            return $this->getEntityTwoLabel();
        }
        throw new \InvalidArgumentException("Entity is not involved in this relation");
    }

    /**
     * @return string
     */
    public function getEntityOneLabel(): string
    {
        if ($this->entityOneLabel) {
            return $this->entityOneLabel;
        }
        return $this->type === self::TYPE_ONE_TO_MANY
            ? $this->getEntityOneInstance()->getLabelSingular()
            : $this->getEntityOneInstance()->getLabelPlural();
    }

    /**
     * @return string
     */
    public function getEntityTwoLabel(): string
    {
        if ($this->entityTwoLabel) {
            return $this->entityTwoLabel;
        }
        return $this->type === self::TYPE_ONE_TO_MANY
            ? $this->getEntityTwoInstance()->getLabelSingular()
            : $this->getEntityTwoInstance()->getLabelPlural();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code . ($this->code && !\str_ends_with($this->code, '_') ? '_' : '');
    }

    /**
     * @return bool
     */
    public function isEntityOneFrontend(): bool
    {
        return $this->entityOneFrontend;
    }

    /**
     * @return bool
     */
    public function isEntityTwoFrontend(): bool
    {
        return $this->entityTwoFrontend;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return implode(
            '##',
            [
                $this->getEntityOneInstance()->getNameSingular(),
                $this->getEntityTwoInstance()->getNameSingular(),
                $this->getCode()
            ]
        );
    }
}
