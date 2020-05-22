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

namespace App\Umc\CoreBundle\Config\Form\Modifier;

use App\Umc\CoreBundle\Config\Modifier\ModifierInterface;

class Tab implements ModifierInterface
{
    /**
     * @param array $config
     * @return array
     */
    public function modify(array $config): array
    {
        foreach ($config as $type => $item) {
            foreach ($item['fields'] ?? [] as $key => $field) {
                $tab = $field['tab'] ?? '';
                if (isset($item['tabs'][$tab])) {
                    $item['tabs'][$tab]['fields'] = $item['tabs'][$tab]['fields'] ?? [];
                    $item['tabs'][$tab]['fields'][$key] = $field;
                }
            }
            unset($item['fields']);
            $config[$type] = $item;
        }
        return $config;
    }
}
