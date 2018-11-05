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

use App\Util\YamlLoader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class BaseType
 * @package App\Form
 */
class BaseType extends AbstractType
{
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
    private $typeMap;

    /**
     * BaseType constructor.
     * @param YamlLoader $loader
     * @param string $type
     * @param array $typeMap
     */
    public function __construct(
        YamlLoader $loader,
        string $type,
        array $typeMap
    ) {
        $this->loader = $loader;
        $this->type = $type;
        $this->typeMap = $typeMap;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = $this->loader->load($this->type);
        foreach ($fields as $field) {
            $type = $field['type'] ?? '';
            $options = $field['options'] ?? [];
            if (isset($this->typeMap[$type])) {
                $fieldTypeConfig = $this->typeMap[$type];
            } else {
                throw new \Exception('Unsuported Form element type ' . $type);
            }
            if (!isset($fieldTypeConfig['type'])) {
                continue;
            }
            $fieldType = $fieldTypeConfig['type'];
            if (isset($fieldTypeConfig['choice_config'])) {
                $choices = [];
                foreach ($fieldTypeConfig['choice_config'] as $key => $settings) {
                    if (isset($settings['label'])) {
                        $choices[$settings['label']] = $key;
                    }
                }
                $options['choices'] = $choices;
            }
            if (!isset($options['attr'])) {
                $options['attr'] = [];
            }
            $options['attr']['data-toggle'] = 'tooltip';
            $builder->add($field['id'], $fieldType, $options);
        }
    }
}
