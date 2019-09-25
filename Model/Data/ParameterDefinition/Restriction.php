<?php

namespace Macopedia\Allegro\Model\Data\ParameterDefinition;

use Macopedia\Allegro\Api\Data\ParameterDefinition\RestrictionInterface;
use Magento\Framework\DataObject;

class Restriction extends DataObject implements RestrictionInterface
{

    const TYPE_FIELD_NAME = 'type';
    const VALUE_FIELD_NAME = 'value';

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $value);
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }
}
