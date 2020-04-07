<?php
declare(strict_types=1);

namespace App\Model;

class AttributeTypeDropdown extends AttributeType
{
    /**
     * @var bool
     */
    private $isText;

    /**
     * @return string
     */
    public function getSchemaType(): string
    {
        return $this->isTextAttribute() ? 'varchar' : parent::getSchemaType();
    }

    /**
     * @return string
     */
    public function getSchemaAttributes(): string
    {
        return $this->isTextAttribute() ? ' length="255"' : parent::getSchemaAttributes();
    }

    /**
     * @return bool
     */
    private function isTextAttribute(): bool
    {
        if ($this->isText === null) {
            $this->isText = false;
            foreach ($this->getAttribute()->getOptions() as $option) {
                if (!is_numeric($option->getValue())) {
                    $this->isText = true;
                    break;
                }
            }
        }
        return $this->isText;
    }
}
