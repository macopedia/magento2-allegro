<?php

namespace Macopedia\Allegro\Model\Data\ParameterDefinition;

use Macopedia\Allegro\Api\Data\ParameterDefinition\RestrictionInterface;
use Magento\Framework\DataObject;

class Restriction extends DataObject implements RestrictionInterface
{
    const TYPE_FIELD_NAME = 'type';
    const VALUE_FIELD_NAME = 'value';

    /**
     * {@inheritDoc}
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function setValue(mixed $value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): mixed
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }
}
