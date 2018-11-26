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

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class Full extends AbstractType
{
    const DEFAULT_BLOCK_PREFIX = 'data';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder->add('module', ModuleType::class);
        $builder->add(
            $builder->create('entity', FormType::class)->add(
                $builder->create('__entity__', EntityType::class)
            )
        );
        $builder->add(
            $builder->create('attribute', FormType::class)->add(
                $builder->create('__entity__', FormType::class)->add(
                    $builder->create('__attribute__', AttributeType::class)
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix() : string
    {
        return self::DEFAULT_BLOCK_PREFIX;
    }
}
