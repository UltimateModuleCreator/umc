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

use App\Service\ModuleList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Index extends AbstractController
{
    /**
     * @var ModuleList
     */
    private $moduleList;
    /**
     * @var string
     */
    private $template;

    /**
     * Index constructor.
     * @param ModuleList $moduleList
     * @param string $template
     */
    public function __construct(ModuleList $moduleList, $template)
    {
        $this->moduleList = $moduleList;
        $this->template = $template;
    }

    /**
     * @return Response
     */
    public function run() : Response
    {
        return $this->render(
            $this->template,
            [
                'modules' => $this->moduleList->getModules()
            ]
        );
    }
}
