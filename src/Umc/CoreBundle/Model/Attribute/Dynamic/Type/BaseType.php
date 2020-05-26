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

namespace App\Umc\CoreBundle\Model\Attribute\Dynamic\Type;

use App\Umc\CoreBundle\Model\Attribute\Dynamic;
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
     * @var Dynamic
     */
    private $dynamic;
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
    private $processor;
    /**
     * @var array
     */
    private $templates;

    /**
     * BaseType constructor.
     * @param Environment $twig
     * @param Dynamic $dynamic
     * @param array $data
     */
    public function __construct(Environment $twig, Dynamic $dynamic, array $data)
    {
        $this->twig = $twig;
        $this->dynamic = $dynamic;
        $this->label = (string)($data['label'] ?? null);
        $this->canHaveOptions = (bool)($data['can_have_options'] ?? false);
        $this->canBeRequired = (bool)($data['can_be_required'] ?? false);
        $this->multiple = (bool)($data['multiple'] ?? false);
        $this->sourceModel = $data['source_model'] ?? null;
        $this->processor = $data['processor'] ?? [];
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
        return $this->sourceModel ?? $this->dynamic->getOptionSourceVirtualType();
    }

    /**
     * @return Dynamic
     */
    public function getDynamic(): Dynamic
    {
        return $this->dynamic;
    }

    /**
     * @param string $template
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function renderTemplate(string $template): string
    {
        $attribute = $this->dynamic->getAttribute();
        $entity = $attribute->getEntity();
        $module = $entity->getModule();
        return $this->twig->render(
            $template,
            [
                'type' => $this,
                'field' => $this->dynamic,
                'attribute' => $attribute,
                'entity' => $entity,
                'module' => $module,
                'indent' => $this->dynamic->isExpanded() ? '' : str_repeat(' ', 4)
            ]
        );
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
}
