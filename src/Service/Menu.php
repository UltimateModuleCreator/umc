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

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class Menu
{
    /**
     * @var array
     */
    private $config;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Menu constructor.
     * @param RouterInterface $router
     * @param RequestStack $requestStack
     * @param array $config
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack, array $config)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function render()
    {
        $html = '';
        if (count($this->config) > 0) {
            $html .= '<ul class="nav side-menu">';
            foreach ($this->config as $id => $item) {
                $html .= $this->renderItem($item, $id);
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * @return array
     */
    private function getSelectedPaths()
    {
        $path = trim($this->requestStack->getCurrentRequest()->getPathInfo(), '/');
        $parts = explode('/', $path);
        $selected = [];
        foreach ($parts as $index => $value) {
            if (!isset($selected[$index - 1])) {
                $selected[$index] = $value;
            } else {
                $selected[$index] = $selected[$index - 1] . '/' . $value;
            }
        }
        return $selected;
    }

    /**
     * @param array $item
     * @param $id
     * @param $selected
     * @return string
     */
    private function renderItem(array $item, $id)
    {
        $selected = $this->getSelectedPaths();
        $class = '';
        if ($this->hasChildren($item)) {
            $class .= ' treeview';
        }
        $activeWhen = [];
        if (isset($item['active'])) {
            $activeWhen = array_intersect($selected, $item['active']);
        }
        if (count($activeWhen) > 0) {
            $class .= ' active';
        }
        $html = '<li' . (($class) ? ' class="' . $class . '"' : '') . ' id="menu-item-' . $id . '">';
        $params = $item['url-params'] ?? [];
        $url = (array_key_exists('url', $item) ? $this->router->generate($item['url'], $params) : '#');
        $html .= '<a href="' . $url . '">';
        if (isset($item['icon'])) {
            $html .= '<i class="' . $item['icon'] . '"></i>';
        }
        $html .= '<span>' . $item['label'] . '</span>';
        if ($this->hasChildren($item)) {
            $html .= '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>';
        }
        $html .= "</a>";
        if ($this->hasChildren($item)) {
            $html .= '<ul class="treeview-menu">';
            foreach ($item['children'] as $childId => $child) {
                $html .= $this->renderItem($child, $id . '-' . $childId);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }
    /**
     * @param array $item
     * @return bool
     */
    private function hasChildren(array $item)
    {
        return isset($item['children']) && is_array($item['children']) && count($item['children']);
    }
}
