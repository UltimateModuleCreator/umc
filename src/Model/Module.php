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

namespace App\Model;

class Module extends AbstractModel
{
    /**
     * @var array
     */
    protected $propertyNames = [
        'namespace', 'module_name', 'version', 'menu_parent',
        'sort_order', 'menu_text', 'license','composer_description'
    ];
    /**
     * @var Entity[]
     */
    private $entities = [];
    /**
     * @var string[]
     */
    private $formattedLicense = [];

    /**
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param Entity $entity
     */
    public function addEntity(Entity $entity) : void
    {
        $entity->setModule($this);
        $this->entities[] = $entity;
    }

    /**
     * @return array
     */
    public function getAdditionalToArray() : array
    {
        $result = [];
        $result['_entities'] = array_map(
            function (Entity $item) {
                return $item->toArray();
            },
            $this->getEntities()
        );
        return $result;
    }

    /**
     * @return string
     */
    public function getProcessedLicense() : string
    {
        $license = $this->getData('license');
        if (is_string($license)) {
            $license = trim($license);
        }
        if (!$license) {
            return '';
        }
        $replace = [
            '{{Namespace}}' => $this->getData('namespace'),
            '{{Module}}' => $this->getData('module_name'),
            '{{Y}}' => date('Y')
        ];
        return str_replace(
            array_keys($replace),
            array_values($replace),
            $license
        );
    }

    /**
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function getFormattedLicense(string $format) : string
    {
        $license = $this->getProcessedLicense();
        if (!$license) {
            return '';
        }
        if (!isset($this->formattedLicense[$format])) {
            if ($format === 'php') {
                $this->formattedLicense[$format] = $this->formatPhpLicense($license);
            } elseif ($format === "xml") {
                $this->formattedLicense[$format] = $this->formatXmlLicense($license);
            } else {
                throw new \Exception("Unsupported licenece formatter {$format}");
            }
        }
        return $this->formattedLicense[$format];
    }

    /**
     * @param string $license
     * @return string
     */
    private function formatPhpLicense(string $license) : string
    {
        $eol = PHP_EOL;
        $license = trim($license);
        while (strpos($license, '*/') !== false) {
            $license = str_replace('*/', '', $license);
        }
        while (strpos($license, '/*') !== false) {
            $license = str_replace('/*', '', $license);
        }
        while (strpos($license, '<!--') !== false) {
            $license = str_replace('<!--', '', $license);
        }
        while (strpos($license, '-->') !== false) {
            $license = str_replace('-->', '', $license);
        }
        $lines = explode("\n", $license);
        $top = "\n";
        $processed = $top . '/**' . $eol;
        foreach ($lines as $line) {
            $processed .= ' * ' . trim($line) . $eol;
        }
        $processed .= ' */' . $eol;
        return $processed;
    }

    /**
     * @param string $license
     * @return string
     */
    private function formatXmlLicense(string $license) : string
    {
        $eol = PHP_EOL;
        $license = trim($license);
        while (strpos($license, '*/') !== false) {
            $license = str_replace('*/', '', $license);
        }
        while (strpos($license, '/*') !== false) {
            $license = str_replace('/*', '', $license);
        }
        while (strpos($license, '<!--') !== false) {
            $license = str_replace('<!--', '', $license);
        }
        while (strpos($license, '-->') !== false) {
            $license = str_replace('-->', '', $license);
        }
        $lines = explode("\n", $license);
        $top = $eol . "<!--" . $eol;
        $footer = $eol . '-->' . $eol;
        $processed = $top . '/**' . $eol;
        foreach ($lines as $line) {
            $processed .= ' * ' . trim($line) . $eol;
        }
        $processed .= ' */' . $footer;
        return $processed;
    }

    /**
     * @return string
     */
    public function getExtensionName() : string
    {
        return $this->getData('namespace') . '_' . $this->getData('module_name');
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasAttributeType($type) : bool
    {
        foreach ($this->getEntities() as $entity) {
            if ($entity->hasAttributeType($type)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function hasSearchableEntities() : bool
    {
        return count($this->getSearchableEntities()) > 0;
    }

    /**
     * @return Entity[]
     */
    public function getSearchableEntities() : array
    {
        return array_filter(
            $this->getEntities(),
            function (Entity $entity) {
                return $entity->getData('search') !== "0";
            }
        );
    }
}
