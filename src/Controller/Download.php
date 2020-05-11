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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;

class Download extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $path;

    /**
     * Download constructor.
     * @param RequestStack $requestStack
     * @param Filesystem $filesystem
     * @param string $path
     */
    public function __construct(RequestStack $requestStack, Filesystem $filesystem, string $path)
    {
        $this->requestStack = $requestStack;
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function run()
    {
        try {
            $module = $this->requestStack->getCurrentRequest()->get('module');
            if (!$module) {
                throw new \Exception("Module not specified");
            }
            $filename = $this->path . '/' . basename($module) . '.zip';
            if ($this->filesystem->exists($filename)) {
                return $this->file($filename);
            }
            throw new \Exception("Module {$module} does not exist");
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('index');
        }
    }
}
