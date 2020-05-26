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

namespace App\Umc\CoreBundle\Model\Attribute\Type;

use App\Umc\CoreBundle\Model\Attribute;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BaseType
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
     * @var string[]
     */
    private $processor;
    /**
     * @var bool
     */
    private $multiple;
    /**
     * @var string|null
     */
    private $gridFilterType;
    /**
     * @var bool
     */
    private $canFilterInGrid;
    /**
     * @var string|null
     */
    protected $sourceModel;
    /**
     * @var array
     */
    private $templates;
    /**
     * @var array
     */
    private $flags;

    /**
     * AttributeType constructor.
     * @param Environment $twig
     * @param Attribute $attribute
     * @param array $data
     */
    public function __construct(Environment $twig, Attribute $attribute, array $data)
    {
        $this->twig = $twig;
        $this->attribute = $attribute;
        $this->label = (string)($data['label'] ?? null);
        $this->gridFilterType = (string)($data['grid_filter_type'] ?? '');
        $this->canFilterInGrid = (bool)($data['can_filter_in_grid'] ?? false);
        $this->typeHint = (string)($data['type_hint'] ?? 'string');
        $this->schemaType = (string)($data['schema_type'] ?? 'varchar');
        $this->schemaAttributes = (string)($data['schema_attributes'] ?? '');
        $this->multiple = (bool)($data['multiple'] ?? false);
        $this->processor = $data['processor'] ?? [];
        $this->sourceModel = $data['source_model'] ?? null;
        $this->flags = $data['flags'] ?? [];
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
     * @return array
     */
    public function getProcessors(): array
    {
        return $this->processor;
    }
    /**
     * @param $type
     * @return array
     */
    public function getProcessorTypes($type): array
    {
        $processorType = $this->processor[$type] ?? null;
        if ($processorType === null) {
            return [];
        }
        if (!is_array($processorType)) {
            return [$processorType];
        }
        return $processorType;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
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
     * @return array
     */
    public function getFlags()
    {
        return array_keys(array_filter($this->flags));
    }

    /**
     * @param string $flag
     * @return bool
     */
    public function getFlag(string $flag)
    {
        return in_array($flag, $this->getFlags());
    }

    /**
     * @param string $templateKey
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTemplate(string $templateKey, $params = []): string
    {
        $template = $this->templates[$templateKey] ?? null;
        if ($template === null) {
            return '';
        }
        $entity = $this->attribute->getEntity();
        $module = $entity->getModule();
        $params = array_merge(
            $params,
            [
                'type' => $this,
                'attribute' => $this->attribute,
                'entity' => $entity,
                'module' => $module
            ]
        );
        return $this->twig->render($template, $params);
    }
}
