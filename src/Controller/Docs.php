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

use App\Service\Source\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Docs extends AbstractController
{
    /**
     * @var string
     */
    private $template;

    /**
     * Files constructor.
     * @param string $template
     */
    public function __construct(string $template)
    {
        $this->template = $template;
    }


    /**
     * @return Response
     * @throws \Exception
     */
    public function run(): Response
    {
        return $this->render(
            $this->template,
            [
                'selectedMenu' => 'docs'
            ]
        );
    }
}
