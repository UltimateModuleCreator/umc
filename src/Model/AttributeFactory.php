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

class AttributeFactory implements FactoryInterface
{
    /**
     * @var TypeFactory
     */
    private $typeFactory;

    /**
     * AttributeFactory constructor.
     * @param TypeFactory $typeFactory
     */
    public function __construct(TypeFactory $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param array $data
     * @return Attribute | AbstractModel
     */
    public function create(array $data = []) : Attribute
    {
        return new Attribute($this->typeFactory, $data);
    }
}
