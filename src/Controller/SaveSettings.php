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

namespace App\Controller;

use App\Service\WriterFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SaveSettings extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var WriterFactory
     */
    private $writerFactory;
    /**
     * @var \App\Model\Settings
     */
    private $settingsModel;

    /**
     * SaveSettings constructor.
     * @param RequestStack $requestStack
     * @param WriterFactory $writerFactory
     * @param \App\Model\Settings $settingsModel
     */
    public function __construct(
        RequestStack $requestStack,
        WriterFactory $writerFactory,
        \App\Model\Settings $settingsModel
    ) {
        $this->requestStack = $requestStack;
        $this->writerFactory = $writerFactory;
        $this->settingsModel = $settingsModel;
    }

    /**
     * @return JsonResponse
     */
    public function run() : JsonResponse
    {
        $response = [];
        try {
            $data = $this->requestStack->getCurrentRequest()->get('settings');
            $this->settingsModel->setSettings($data);
            $writer = $this->writerFactory->create($this->settingsModel->getPath());
            $writer->writeFiles([
                $this->settingsModel->getFile() => $this->settingsModel->getSettingsAsYml()
            ]);
            $response['success'] = true;
            $response['message'] = "Default settings have been saved";
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = "There was an error saving the default values: " . $e->getMessage();
        }
        return new JsonResponse($response);
    }
}
