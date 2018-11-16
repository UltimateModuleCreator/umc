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

namespace App\Model\Attribute;

use App\Model\AbstractModel;
use App\Model\Attribute;

class AbstractType extends AbstractModel implements TypeInterface
{
    /**
     * @var Attribute
     */
    protected $attribute;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * AbstractType constructor.
     * @param Attribute $attribute
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig, Attribute $attribute, array $data = [])
    {
        $this->attribute = $attribute;
        $this->twig = $twig;
        parent::__construct($data);
    }

    /**
     * @return Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * @param $type
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function render($type)
    {
        if (!$this->getData($type)) {
            return '';
        }
        return $this->twig->render(
            $this->getData($type),
            [
                'type' => $this,
                'attribute' => $this->getAttribute(),
                'entity' => $this->getAttribute()->getEntity(),
                'module' => $this->getAttribute()->getEntity()->getModule()
            ]
        );
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderGrid() : string
    {
        return $this->render('grid_template');
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderForm() : string
    {
        return $this->render('form_template');
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderFk() : string
    {
        return $this->render('fk_template');
    }

    /**
     * @return array
     */
    protected function getAttributeColumnSettings() : array
    {
        $options = [];
        if ($this->getAttribute()->getData('required')) {
            $options['nullable'] = 'false';
        }
        return $options;
    }

    /**
     * @param int $padd
     * @return string
     */
    public function getAttributeColumnSettingsString($padd = 4) : string
    {
        $tab = str_repeat(' ', 4);
        $padding = str_repeat($tab, $padd);
        $string = $padding.'['.PHP_EOL;
        foreach ($this->getAttributeColumnSettings() as $key => $value) {
            $string .= $padding.$tab."'".$key."' => ".$value.','.PHP_EOL;
        }
        $string .= $padding.']';
        return $string;
    }
}
