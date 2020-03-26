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

namespace App\Model;

class AttributeType
{
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string|null
     */
    private $gridFilterType;
    /**
     * @var string|null
     */
    private $gridTemplate;
    /**
     * @var string|null
     */
    private $schemaFkTemplate;
    /**
     * @var string
     */
    private $frontendViewTemplate;
    /**
     * @var string
     */
    private $frontendListTemplate;
    /**
     * @var bool
     */
    private $canBeName;
    /**
     * @var bool
     */
    private $canShowInGrid;
    /**
     * @var bool
     */
    private $canFilterInGrid;
    /**
     * @var bool
     */
    private $canHaveOptions;
    /**
     * @var bool
     */
    private $canBeRequired;
    /**
     * @var bool
     */
    private $fullText;
    /**
     * @var string
     */
    private $typeHint;
    /**
     * @var string
     */
    private $schemaType;
    /**
     * @var string
     */
    private $schemaAttributes;
    /**
     * @var bool
     */
    private $upload;
    /**
     * @var bool
     */
    private $dataProcessorRequired;
    /**
     * @var bool
     */
    private $multiple;

    /**
     * AttributeType constructor.
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(Attribute $attribute, array $data)
    {
        $this->attribute = $attribute;
        $this->label = (string)($data['label'] ?? null);
        $this->gridFilterType = (string)($data['grid_filter_type'] ?? '');
        $this->gridTemplate = $data['grid_template'] ? (string) $data['grid_template'] : null;
        $this->gridTemplate = $data['form_template'] ? (string) $data['form_template'] : null;
        $this->schemaFkTemplate = $data['schema_fk_template'] ? (string) $data['schema_fk_template'] : null;
        $this->frontendViewTemplate = $data['frontend_view_template']
            ? (string) $data['frontend_view_template']
            : null;
        $this->frontendListTemplate = $data['frontend_view_template']
            ? (string) $data['frontend_list_template']
            : null;
        $this->canBeName = (bool)($data['can_be_name'] ?? false);
        $this->canShowInGrid = (bool)($data['can_show_in_grid'] ?? false);
        $this->canFilterInGrid = (bool)($data['can_filter_in_grid'] ?? false);
        $this->canHaveOptions = (bool)($data['can_have_options'] ?? false);
        $this->canBeRequired = (bool)($data['can_be_required'] ?? false);
        $this->fullText = (bool)($data['full_text'] ?? false);
        $this->typeHint = (string)($data['type_hint'] ?? 'string');
        $this->schemaType = (string)($data['schema_type'] ?? 'varchar');
        $this->schemaAttributes = (string)($data['schema_attributes'] ?? 'langth="255"');
        $this->upload = (bool)($data['upload'] ?? false);
        $this->multiple = (bool)($data['multiple'] ?? false);
        $this->dataProcessorRequired = (bool)($data['data_processor_required'] ?? false);
    }

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return null|string
     */
    public function getGridFilterType(): ?string
    {
        return $this->gridFilterType;
    }

    /**
     * @return null|string
     */
    public function getGridTemplate(): ?string
    {
        return $this->gridTemplate;
    }

    /**
     * @return null|string
     */
    public function getSchemaFkTemplate(): ?string
    {
        return $this->schemaFkTemplate;
    }

    /**
     * @return string
     */
    public function getFrontendViewTemplate(): string
    {
        return $this->frontendViewTemplate;
    }

    /**
     * @return string
     */
    public function getFrontendListTemplate(): string
    {
        return $this->frontendListTemplate;
    }

    /**
     * @return bool
     */
    public function isCanBeName(): bool
    {
        return $this->canBeName;
    }

    /**
     * @return bool
     */
    public function isCanShowInGrid(): bool
    {
        return $this->canShowInGrid;
    }

    /**
     * @return bool
     */
    public function isCanHaveOptions(): bool
    {
        return $this->canHaveOptions;
    }

    /**
     * @return bool
     */
    public function isCanBeRequired(): bool
    {
        return $this->canBeRequired;
    }

    /**
     * @return bool
     */
    public function isFullText(): bool
    {
        return $this->fullText;
    }

    /**
     * @return string
     */
    public function getTypeHint(): string
    {
        return $this->typeHint;
    }

    /**
     * @return string
     */
    public function getSchemaType(): string
    {
        return $this->schemaType;
    }

    /**
     * @return string
     */
    public function getSchemaAttributes(): string
    {
        return $this->schemaAttributes;
    }

    /**
     * @return bool
     */
    public function isCanFilterInGrid(): bool
    {
        return $this->canFilterInGrid;
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        return $this->upload;
    }

    /**
     * @return bool
     */
    public function isDataProcessorRequired(): bool
    {
        return $this->dataProcessorRequired;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @return bool
     */
    public function isProductAttribute()
    {
        return in_array($this->attribute->getType(), ['product_attribute', 'product_attribute_multiselect']);
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet()
    {
        return in_array($this->attribute->getType(), ['product_attribute_set', 'product_attribute_set_multiselect']);
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return implode(',', array_map('trim', explode("\n", $this->attribute->getRawDefaultValue())));
    }
}
