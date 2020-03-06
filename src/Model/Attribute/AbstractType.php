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
    const GRID_TEMPLATE = 'grid_template';
    const FORM_TEMPLATE = 'form_template';
    const FK_TEMPLATE = 'fk_template';
    const FRONTEND_VIEW_TEMPLATE = 'frontend_view_template';
    const FRONTEND_LIST_TEMPLATE = 'frontend_list_template';
    const GRID_FILTER_TYPE = 'grid_filter_type';
    const MULTIPLE = 'multiple';
    const MULTIPLE_TEXT = 'multiple_text';
    const UPLOAD_TYPE = 'upload_type';
    const SQL_TYPE_CONSTANT = 'sql_type_constant';
    const SQL_SIZE = 'sql_size';
    const CAN_HAVE_OPTIONS = 'can_have_options';
    const TYPE_HINT = 'type_hint';
    const SOURCE_MODEL = 'source_model';
    const SCHEMA_TYPE = 'schema_type';
    const SCHEMA_ATTRIBUTES = 'schema_attributes';
    const SCHEMA_FK_TEMPLATE = 'schema_fk_template';
    const FULL_TEXT = 'full_text';
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
     * @param \Twig_Environment $twig
     * @param Attribute $attribute
     * @param array $data
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
    protected function render($type) : string
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
        return $this->render(self::GRID_TEMPLATE);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderForm() : string
    {
        return $this->render(self::FORM_TEMPLATE);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderFk() : string
    {
        return $this->render(self::FK_TEMPLATE);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderFrontendView() : string
    {
        return $this->render(self::FRONTEND_VIEW_TEMPLATE);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderFrontendList() : string
    {
        return $this->render(self::FRONTEND_LIST_TEMPLATE);
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderSchemaFk() : string
    {
        return $this->render(self::SCHEMA_FK_TEMPLATE);
    }

    /**
     * @return array
     */
    protected function getAttributeColumnSettings() : array
    {
        $options = [];
        if ($this->getAttribute()->getRequired()) {
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
        $string = $padding . '[' . PHP_EOL;
        foreach ($this->getAttributeColumnSettings() as $key => $value) {
            $string .= $padding . $tab . "'" . $key . "' => " . $value . ',' . PHP_EOL;
        }
        $string .= $padding . ']';
        return $string;
    }

    /**
     * @return string
     */
    public function getAttributeColumnSettingsStringXml() : string
    {
        $attributes = trim($this->getData(self::SCHEMA_ATTRIBUTES, ''));
        if (strlen($attributes) > 0) {
            $attributes .= ' ';
        }
        if ($this->getAttribute()->getRequired()) {
            $attributes .= 'nullable="false"';
        } else {
            $attributes .= 'nullable="true"';
        }
        return $attributes;
    }

    /**
     * @return null|string
     */
    public function getGridFilterType() : ?string
    {
        return $this->getData(self::GRID_FILTER_TYPE);
    }

    /**
     * @return bool
     */
    public function getMultiple() : bool
    {
        return (bool)$this->getData(self::MULTIPLE);
    }

    /**
     * @return null|string
     */
    public function getMultipleText() : ?string
    {
        return $this->getData(self::MULTIPLE_TEXT);
    }

    /**
     * @return null|string
     */
    public function getUploadType() : ?string
    {
        return $this->getData(self::UPLOAD_TYPE);
    }

    /**
     * @return bool
     */
    public function isUpload(): bool
    {
        return $this->getUploadType() !== null;
    }

    /**
     * @return null|string
     */
    public function getSqlTypeConstant() : ?string
    {
        return $this->getData(self::SQL_TYPE_CONSTANT);
    }

    /**
     * @return null|string
     */
    public function getSqlSize() : ?string
    {
        return $this->getData(self::SQL_SIZE);
    }

    /**
     * @return bool
     */
    public function getCanHaveOptions() : bool
    {
        return (bool)$this->getData(self::CAN_HAVE_OPTIONS);
    }

    /**
     * @return null|string
     */
    public function getTypeHint() : ?string
    {
        return $this->getData(self::TYPE_HINT);
    }

    /**
     * @return null|string
     */
    public function getSourceModel() : ?string
    {
        return $this->getData(self::SOURCE_MODEL);
    }

    /**
     * @return null|string
     */
    public function getSchemaType() : ?string
    {
        return $this->getData(self::SCHEMA_TYPE);
    }

    /**
     * @return bool
     */
    public function getFullText() : bool
    {
        return (bool)$this->getData(self::FULL_TEXT);
    }
}
