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

use App\Model\ModuleFactory;
use App\Service\Builder;
use App\Service\ModuleLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class Save extends AbstractController
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Builder
     */
    private $builder;
    /**
     * @var ModuleFactory
     */
    private $moduleFactory;

    /**
     * Save constructor.
     * @param RequestStack $requestStack
     * @param Builder $builder
     * @param ModuleFactory $moduleFactory
     */
    public function __construct(
        RequestStack $requestStack,
        Builder $builder,
        ModuleFactory $moduleFactory
    ) {
        $this->requestStack = $requestStack;
        $this->builder = $builder;
        $this->moduleFactory = $moduleFactory;
    }

    /**
     * @return JsonResponse
     */
    public function run() : JsonResponse
    {
        try {
            $response = [];
            $data = $this->requestStack->getCurrentRequest()->get('data');
            $module = $this->moduleFactory->create($data);
            $this->builder->buildModule($module);
            $response['success'] = true;
            $response['message'] = "You have created the module " . $module->getExtensionName();
            $response['link'] = $this->generateUrl('download', ['module' => $module->getExtensionName()]);
            $response['module'] = $module->getExtensionName();
        } catch (\Exception $e) {
            $response['success'] = false;
//            $response['message'] = '<pre>' . print_r($module->getComposerDependencies(), true) . '</pre>';
            $response['message'] = $e->getMessage() . '<pre>' . $this->getExceptionTraceAsString($e) . '</pre>';
        }
        return new JsonResponse($response);
    }

    /**
     * @param \Exception $exception
     * @return string
     */
    public function getExceptionTraceAsString(\Exception $exception)
    {
        $rtn = "";
        $count = 0;
        foreach ($exception->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $current_file = "[internal function]";
            if(isset($frame['file'])) {
                $current_file = $frame['file'];
            }
            $current_line = "";
            if(isset($frame['line'])) {
                $current_line = $frame['line'];
            }
            $rtn .= sprintf( "#%s %s(%s): %s(%s)\n",
                $count,
                $current_file,
                $current_line,
                $frame['function'],
                $args );
            $count++;
        }
        return $rtn;
    }
}
