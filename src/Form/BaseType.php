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
 */
declare(strict_types=1);

namespace App\Form;

use App\Form\Field\TransformerInterface;
use App\Util\YamlLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseType
 * @package App\Form
 */
class BaseType extends AbstractType
{
    const DEFAULT_GROUP = 'Misc';
    /**
     * @var YamlLoader
     */
    protected $loader;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var array
     */
    protected $typeMap;
    /**
     * @var TransformerInterface
     */
    protected $fieldTransformer;
    /**
     * @var \App\Model\Settings
     */
    protected $settings;
    /**
     * @var string
     */
    protected $dataKey;

    /**
     * BaseType constructor.
     * @param YamlLoader $loader
     * @param string $type
     * @param array $typeMap
     * @param string $dataKey
     */
    public function __construct(
        YamlLoader $loader,
        TransformerInterface $fieldTransformer,
        string $type,
        \App\Model\Settings $settings,
        array $typeMap,
        string $dataKey = ''
    ) {
        $this->loader = $loader;
        $this->fieldTransformer = $fieldTransformer;
        $this->type = $type;
        $this->settings = $settings;
        $this->typeMap = $typeMap;
        $this->dataKey = $dataKey;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $settings = $this->settings->getSettings();
        foreach ($this->getFields() as $field) {
            $type = $field['type'] ?? '';
            $options = $field['options'] ?? [];
            if (isset($this->typeMap[$type])) {
                $fieldTypeConfig = $this->typeMap[$type];
            } else {
                throw new \Exception('Unsupported Form element type ' . $type);
            }
            if (!isset($fieldTypeConfig['type'])) {
                continue;
            }
            $fieldType = $fieldTypeConfig['type'];
            $choices = $this->getChoices($fieldTypeConfig);
            if ($choices !== null) {
                $options['choices'] = $choices;
            }
            if (!isset($options['attr'])) {
                $options['attr'] = [];
            }
            $options['data'] = $settings[$this->dataKey][$field['id']] ?? null;
            $options['attr']['data-toggle'] = 'tooltip';
            $builder->add($field['id'], $fieldType, $options);
        }
    }

    /**
     * @param array $fieldTypeConfig
     * @return array|null
     */
    protected function getChoices(array $fieldTypeConfig) : ?array
    {
        if (isset($fieldTypeConfig['choice_config'])) {
            $choices = [];
            $groupBy = $fieldTypeConfig['group'] ?? false;
            foreach ($fieldTypeConfig['choice_config'] as $key => $settings) {
                if (!isset($settings['label'])) {
                    continue;
                }
                if ($groupBy) {
                    $groupLabel = $settings[$groupBy] ?? self::DEFAULT_GROUP;
                    $choices[$groupLabel][$settings['label']] = $key;
                } else {
                    $choices[$settings['label']] = $key;
                }
            }
            $sort = $fieldTypeConfig['sort'] ?? false;
            if ($sort) {
                ksort($choices);
            }
            return $choices;
        }
        return null;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getFields() : array
    {
        $fields = [];
        foreach ($this->loader->load($this->type) as $key => $fieldConfig) {
            $field = $this->fieldTransformer->transform($fieldConfig);
            if ($field !== null) {
                $fields[$key] = $field;
            }
        }
        return $fields;
    }
}
