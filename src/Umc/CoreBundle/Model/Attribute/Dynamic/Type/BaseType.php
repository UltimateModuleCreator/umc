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
     * @var string|null
     */
    private $sourceModel;
    /**
     * @var array
     */
    private $processor;
    /**
     * @var array[]
     */
    private $flags;
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
        $this->sourceModel = $data['source_model'] ?? null;
        $this->flags = $data['dynamic_flags'] ?? [];
        $this->templates = isset($data['templates']) && is_array($data['templates']) ? $data['templates'] : [];
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
     * @return array
     */
    public function getFlags(): array
    {
        return array_keys(array_filter($this->flags));
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
        $attribute = $this->dynamic->getAttribute();
        $entity = $attribute->getEntity();
        $module = $entity->getModule();
        $params = array_merge(
            $params,
            [
                'type' => $this,
                'field' => $this->dynamic,
                'attribute' => $attribute,
                'entity' => $entity,
                'module' => $module,
                'indent' => $this->dynamic->isExpanded() ? '' : str_repeat(' ', 4)
            ]
        );
        return $this->twig->render($template, $params);
    }
}
