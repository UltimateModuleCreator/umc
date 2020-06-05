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
    protected $adminGridHidden;
    /**
     * @var bool
     */
    protected $adminGridFilter;
    /**
     * @var string
     */
    protected $note;
    /**
     * @var string
     */
    protected $tooltip;
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
        $this->adminGridHidden = (bool)($data['admin_grid_hidden'] ?? false);
        $this->adminGridFilter = (bool)($data['admin_grid_filter'] ?? false);
        $this->note = (string)($data['note'] ?? '');
        $this->tooltip = (string)($data['tooltip'] ?? '');
        $this->fullText = (bool)($data['full_text'] ?? false);
        $this->expanded = (bool)($data['expanded'] ?? false);
    }

    /**
     * @return bool
     */
    public function isAdminGrid(): bool
    {
        return $this->adminGrid && $this->getTypeInstance()->hasFlag('can_show_in_grid');
    }

    /**
     * @return bool
     */
    public function isAdminGridHidden(): bool
    {
        return $this->isAdminGrid() && $this->adminGridHidden;
    }

    /**
     * @return bool
     */
    public function isAdminGridFilter(): bool
    {
        return $this->isAdminGrid() && $this->adminGridFilter && $this->getTypeInstance()->isCanFilterInGrid();
    }

    /**
     * @return string
     */
    public function getNote(): string
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getTooltip(): string
    {
        return $this->tooltip;
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
        $result['admin_grid_hidden'] = $this->adminGridHidden;
        $result['admin_grid_filter'] = $this->adminGridFilter;
        $result['note'] = $this->note;
        $result['tooltip'] = $this->tooltip;
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
