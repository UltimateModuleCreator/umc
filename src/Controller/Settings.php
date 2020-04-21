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

namespace App\Controller;

use App\Service\Form\Loader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Settings extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $template;
    /**
     * @var \App\Model\Settings
     */
    private $settingsModel;
    /**
     * @var Loader
     */
    private $formLoader;

    /**
     * Settings constructor.
     * @param Environment $twig
     * @param string $template
     * @param \App\Model\Settings $settingsModel
     * @param Loader $formLoader
     */
    public function __construct(
        Environment $twig,
        string $template,
        \App\Model\Settings $settingsModel,
        Loader $formLoader
    ) {
        $this->twig = $twig;
        $this->template = $template;
        $this->settingsModel = $settingsModel;
        $this->formLoader = $formLoader;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run(): Response
    {
        $forms = $this->formLoader->getForms();
        $groups = [];
        $perRow = 6;
        $allFields = [];
        foreach ($forms as $formKey => $form) {
            $fields = array_reduce(
                $form['rows'],
                function ($all, $row) {
                    return array_merge($row, $all);
                },
                []
            );
            $fields = array_filter(
                $fields,
                function ($item) {
                    return isset($item['has_default']) && $item['has_default'];
                }
            );
            if (count($fields) > 0) {
                $cleanedFields = [];
                foreach ($fields as $key => $field) {
                    $field['name'] = $formKey . '_' . $field['name'];
                    unset($field['additionalDataBind']);
                    $field['col'] = 12 / $perRow;
                    $cleanedFields[$key] = $field;
                    $allFields[] = $field['name'];
                }
                $groups[$formKey] = [
                    'settings_label' => $form['settings_label'] ?? $formKey,
                    'rows' => array_chunk($cleanedFields, $perRow, true)
                ];
            }
        }
        return $this->render(
            $this->template,
            [
                'forms' => $groups,
                'all_fields' => $allFields,
                'selectedMenu' => 'settings',
                'values' => $this->settingsModel->getSettings()
            ]
        );
    }
}
