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

use App\Service\License\ProcessorInterface;

class ModuleFactory implements FactoryInterface
{
    /**
     * @var ProcessorInterface[]
     */
    private $licenseFormatter;

    /**
     * ModuleFactory constructor.
     * @param ProcessorInterface[] $licenseFormatter
     */
    public function __construct(array $licenseFormatter)
    {
        $this->licenseFormatter = $licenseFormatter;
    }

    /**
     * @param array $data
     * @return Module | AbstractModel
     */
    public function create(array $data = []) : Module
    {
        return new Module($this->licenseFormatter, $data);
    }
}
