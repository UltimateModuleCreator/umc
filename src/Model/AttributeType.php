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

use Twig\Environment;

class AttributeType
{
    /**
     * @var Environment
     */
    private $twig;
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
     * @var string|null
     */
    private $sourceModel;
    /**
     * @var array
     */
    private $templates;

    /**
     * AttributeType constructor.
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(Environment $twig, Attribute $attribute, array $data)
    {
        $this->twig = $twig;
        $this->attribute = $attribute;
        $this->label = (string)($data['label'] ?? null);
        $this->gridFilterType = (string)($data['grid_filter_type'] ?? '');
        $this->canBeName = (bool)($data['can_be_name'] ?? false);
        $this->canShowInGrid = (bool)($data['can_show_in_grid'] ?? false);
        $this->canFilterInGrid = (bool)($data['can_filter_in_grid'] ?? false);
        $this->canHaveOptions = (bool)($data['can_have_options'] ?? false);
        $this->canBeRequired = (bool)($data['can_be_required'] ?? false);
        $this->fullText = (bool)($data['full_text'] ?? false);
        $this->typeHint = (string)($data['type_hint'] ?? 'string');
        $this->schemaType = (string)($data['schema_type'] ?? 'varchar');
        $this->schemaAttributes = (string)($data['schema_attributes'] ?? 'length="255"');
        $this->upload = (bool)($data['upload'] ?? false);
        $this->multiple = (bool)($data['multiple'] ?? false);
        $this->dataProcessorRequired = (bool)($data['data_processor_required'] ?? false);
        $this->sourceModel = $data['source_model'] ?? null;
        $this->templates = isset($data['templates']) && is_array($data['templates']) ? $data['templates'] : [];
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
    public function isProductAttribute(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function getDefaultValue(): string
    {
        return implode(',', array_map('trim', explode("\n", $this->attribute->getRawDefaultValue())));
    }

    /**
     * @return string
     */
    public function getMultipleText(): string
    {
        return $this->isMultiple() ? 'true' : 'false';
    }

    /**
     * @return string
     */
    public function getAttributeColumnSettingsStringXml() : string
    {
        $attributes = $this->schemaAttributes;
        if (strlen($attributes) > 0) {
            $attributes .= ' ';
        }
        $attributes .= 'nullable="'. ($this->getAttribute()->isRequired() ? 'false' : 'true') .'"';
        return $attributes;
    }

    /**
     * @return string
     */
    public function getSourceModel(): string
    {
        return $this->sourceModel ?? $this->attribute->getOptionSourceVirtualType();
    }

    /**
     * @return string
     */
    public function getIndexDeleteType(): string
    {
        return $this->getAttribute()->isRequired() ? 'CASCADE' : 'SET NULL';
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderGrid(): string
    {
        return !empty($this->templates['backend']['grid'])
            ? $this->renderTemplate($this->templates['backend']['grid'])
            : '';
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderSchemaFk(): string
    {
        return $this->renderTemplate($this->templates['schema_fk'] ?? null);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderForm(): string
    {
        return !empty($this->templates['backend']['form'])
            ? $this->renderTemplate($this->templates['backend']['form'])
            : '';
    }

    /**
     * @param null|string $template
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderTemplate(?string $template): string
    {
        if (!$template) {
            return '';
        }
        return $this->twig->render(
            $template,
            [
                'type' => $this,
                'attribute' => $this->attribute,
                'entity' => $this->attribute->getEntity(),
                'module' => $this->attribute->getEntity()->getModule()
            ]
        );
    }
}
