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

namespace App\Umc\MagentoBundle\Model;

use App\Umc\CoreBundle\Model\Attribute\Option\Factory as OptionFactory;
use App\Umc\CoreBundle\Model\Attribute\Dynamic\Factory as DynamicFactory;
use App\Umc\CoreBundle\Model\Attribute\Type\Factory as TypeFactory;
use App\Umc\CoreBundle\Model\Entity;
use App\Umc\CoreBundle\Util\StringUtil;

class Attribute extends \App\Umc\CoreBundle\Model\Attribute
{
    /**
     * @var bool
     */
    protected $fullText;
    /**
     * @var bool
     */
    protected $expanded;

    /**
     * Attribute constructor.
     * @param TypeFactory $typeFactory
     * @param OptionFactory $optionFactory
     * @param DynamicFactory $dynamicFactory
     * @param StringUtil $stringUtil
     * @param Entity $entity
     * @param array $data
     */
    public function __construct(
        TypeFactory $typeFactory,
        OptionFactory $optionFactory,
        DynamicFactory $dynamicFactory,
        StringUtil $stringUtil,
        Entity $entity,
        array $data = []
    ) {
        parent::__construct($typeFactory, $optionFactory, $dynamicFactory, $stringUtil, $entity, $data);
        $this->fullText = (bool)($data['full_text'] ?? false);
        $this->expanded = (bool)($data['expanded'] ?? false);
    }

    /**
     * @return bool
     */
    public function isFullText(): bool
    {
        return $this->getTypeInstance()->hasFlag('full_text') && $this->fullText;
    }

    /**
     * @return bool
     */
    public function isExpanded(): bool
    {
        return $this->expanded;
    }

    /**
     * @return string
     */
    public function getOptionSourceVirtualType(): string
    {
        $parts = [
            $this->getEntity()->getModule()->getModuleName(),
            $this->getEntity()->getNameSingular(),
            'Source',
            $this->getCode()
        ];
        return $this->stringUtil->glueClassParts($parts, '');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $result = parent::toArray();
        $result['full_text'] = $this->fullText;
        $result['expanded'] = $this->expanded;
        return $result;
    }

    /**
     * @return array
     */
    public function getFlags()
    {
        $flags = parent::getFlags();
        $this->isFullText() && $flags[] = 'is_full_text';
        return $flags;
    }


    /**
     * @return string
     */
    public function getOptionType(): string
    {
        return $this->areOptionsNumerical() ? 'number' : 'string';
    }
}
