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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Settings extends AbstractController
{
    /**
     * @var \Twig_Environment
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
     * Settings constructor.
     * @param \Twig_Environment $twig
     * @param string $template
     * @param \App\Model\Settings $settingsModel
     */
    public function __construct(\Twig_Environment $twig, string $template, \App\Model\Settings $settingsModel)
    {
        $this->twig = $twig;
        $this->template = $template;
        $this->settingsModel = $settingsModel;
    }

    /**
     * @return Response
     */
    public function run() : Response
    {
        $form = $this->createForm(
            \App\Form\Settings::class,
            $this->settingsModel->getSettings(true),
            array(
                'action' => $this->generateUrl('save-settings'),
                'method' => 'POST',
            )
        );
        return $this->render(
            $this->template,
            [
                'form' => $form->createView(),
                'title' => 'Default Settings',
            ]
        );
    }
}
