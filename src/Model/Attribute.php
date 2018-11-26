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

use App\Model\Attribute\TypeFactory;
use App\Model\Attribute\TypeInterface;

class Attribute extends AbstractModel
{
    /**
     * @var TypeInterface
     */
    private $typeInstance;
    /**
     * @var TypeFactory
     */
    private $typeFactory;
    /**
     * @var Entity
     */
    private $entity;
    /**
     * @var array
     */
    private $processedOptions;

    /**
     * Attribute constructor.
     * @param TypeFactory $typeFactory
     * @param array $data
     */
    public function __construct(TypeFactory $typeFactory, array $data = [])
    {
        $this->typeFactory = $typeFactory;
        parent::__construct($data);
    }

    /**
     * @var array
     */
    protected $propertyNames = [
        'code', 'label', 'type', 'is_name', 'required',
        'options', 'position', 'note', 'admin_grid',
        'admin_grid_filter', 'editor', 'default_value'
    ];

    /**
     * @return Entity
     */
    public function getEntity() : Entity
    {
        return $this->entity;
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity) : void
    {
        $this->entity = $entity;
    }

    /**
     * @return TypeInterface
     * @throws \Exception
     */
    public function getTypeInstance() : TypeInterface
    {
        if ($this->typeInstance === null) {
            $this->typeInstance = $this->typeFactory->create($this);
        }
        return $this->typeInstance;
    }

    /**
     * @return array
     */
    public function getProcessedOptions() : array
    {
        if ($this->processedOptions === null) {
            $this->processedOptions = [];
            $options = $this->getData('options');
            if ($options != null) {
                $options = explode("\n", $options);
                foreach ($options as $key => $option) {
                    $option = trim($option);
                    $this->processedOptions[$this->toConstantName($option)] = [
                        'value' => $key + 1,
                        'label' => $option
                    ];
                }
            }
        }
        return $this->processedOptions;
    }

    /**
     * transform string to constant name
     *
     * @param string $string
     * @return string
     */
    protected function toConstantName($string) : string
    {
        $string = str_replace(' ', '_', $string);
        $processed =  preg_replace(
            '/[^A-Za-z0-9_]/',
            '',
            $string
        );
        $processed = strtoupper($processed);
        if (strlen($processed) == 0) {
            return '_EMPTY';
        }
        $first = substr($processed, 0, 1);
        if (is_numeric($first)) {
            $processed = '_' . $processed;
        }
        return $processed;
    }
}
