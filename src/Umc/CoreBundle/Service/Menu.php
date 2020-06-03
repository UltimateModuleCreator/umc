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

declare(strict_types=1);

namespace App\Umc\CoreBundle\Service;

use App\Umc\CoreBundle\Model\Platform;
use App\Umc\CoreBundle\Model\Platform\Pool;
use App\Umc\CoreBundle\Model\Platform\Version;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Menu
{
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var Pool
     */
    private $pool;

    /**
     * Menu constructor.
     * @param UrlGeneratorInterface $router
     * @param RequestStack $requestStack
     * @param Pool $pool
     */
    public function __construct(UrlGeneratorInterface $router, RequestStack $requestStack, Pool $pool)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->pool = $pool;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        $items = [];
        $items[] = [
            'label' => 'Platforms',
            'url' => $this->router->generate('index'),
            'children' => []
        ];
        foreach ($this->pool->getPlatforms() as $platform) {
            $item = [
                'label' => $platform->getName(),
                'url' => '#',
            ];
            $children = [];
            if ($platform->isSupported()) {
                $children = array_merge(
                    $children,
                    $this->getPlatformGroupLinks($platform, 'new', 'Create Module')
                );
                $children = array_merge(
                    $children,
                    $this->getPlatformGroupLinks($platform, 'settings', 'Settings')
                );
            }
            $children = array_merge(
                $children,
                $this->getPlatformGroupLinks($platform, 'list', 'Created Modules')
            );
            $item['children'] = $children;
            $items[] = $item;
        }
        return $items;
    }

    /**
     * @param Platform $platform
     * @param string $urlPath
     * @param string $label
     * @return array|string[]
     */
    private function getPlatformGroupLinks(Platform $platform, string $urlPath, string $label)
    {
        $hasMultipleVersions = count($platform->getVersions())  > 0;
        $links = ($hasMultipleVersions) ? $this->getVersionLinks($platform, $urlPath) : [];
        $mainItem = [
            'label' => $label,
            'url' => $this->router->generate($urlPath, ['platform' => $platform->getCode()]),
        ];
        array_unshift($links, $mainItem);
        return $links;
    }

    /**
     * @param Platform $platform
     * @param $route
     * @return array|string[]
     */
    private function getVersionLinks(Platform $platform, $route): array
    {
        return array_values(
            array_map(
                function (Version $version) use ($route, $platform) {
                    return [
                        'label' => 'version  - ' . $version->getLabel(),
                        'url' => $this->router->generate(
                            $route,
                            ['platform' => $platform->getCode(), 'version' => $version->getCode()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ];
                },
                $platform->getVersions()
            )
        );
    }
}
