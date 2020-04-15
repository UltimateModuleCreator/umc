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

namespace App\Model\Attribute\Serialized\Type;

use App\Model\Attribute\Serialized;
use Twig\Environment;

class BaseType
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var Serialized
     */
    private $serialized;
    /**
     * @var string
     */
    private $label;
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
     * BaseType constructor.
     * @param Environment $twig
     * @param Serialized $serialized
     * @param array $data
     */
    public function __construct(Environment $twig, Serialized $serialized, array $data)
    {
        $this->twig = $twig;
        $this->serialized = $serialized;
        $this->label = (string)($data['label'] ?? null);
        $this->canHaveOptions = (bool)($data['can_have_options'] ?? false);
        $this->canBeRequired = (bool)($data['can_be_required'] ?? false);
        $this->multiple = (bool)($data['multiple'] ?? false);
        $this->sourceModel = $data['source_model'] ?? null;
        $this->templates = isset($data['templates']) && is_array($data['templates']) ? $data['templates'] : [];
    }

    /**
     * @return string
     */
    public function getMultipleText(): string
    {
        return $this->multiple ? 'true' : 'false';
    }

    /**
     * @return bool
     */
    public function isCanHaveOptions(): bool
    {
        return $this->canHaveOptions;
    }

    /**
     * @return string
     */
    public function getSourceModel(): string
    {
        return $this->sourceModel ?? $this->serialized->getOptionSourceVirtualType();
    }

    /**
     * @return Serialized
     */
    public function getSerialized(): Serialized
    {
        return $this->serialized;
    }

    /**
     * @return bool
     */
    public function isProductAttributeSet(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isProductAttribute(): bool
    {
        return false;
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderForm(): string
    {
        return !empty($this->templates['serialized']['backend'])
            ? $this->renderTemplate($this->templates['serialized']['backend'])
            : '';
    }

    /**
     * @param string $template
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function renderTemplate(string $template): string
    {
        $attribute = $this->serialized->getAttribute();
        $entity = $attribute->getEntity();
        $module = $entity->getModule();
        return $this->twig->render(
            $template,
            [
                'type' => $this,
                'field' => $this->serialized,
                'attribute' => $attribute,
                'entity' => $entity,
                'module' => $module,
                'indent' => $this->serialized->isExpanded() ? '' : str_repeat(' ', 4)
            ]
        );
    }
}
