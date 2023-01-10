<?php

namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\ValuesInterface;
use Macopedia\Allegro\Model\Data\Parameter;

class Values extends Parameter implements ValuesInterface
{
    const VALUE_FIELD_NAME = 'value';

    /**
     * {@inheritDoc}
     */
    public function setValue(array $value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $this->stripEmptyValues($value));
    }

    /**
     * {@inheritDoc}
     */
    public function getValue(): array
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        parent::setRawData($rawData);
        $this->setValue($rawData['values'] ?? []);
    }

    /**
     * {@inheritDoc}
     */
    public function isValueEmpty(): bool
    {
        return count($this->getValue()) < 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getRawData(): array
    {
        return [
            'id' => $this->getId(),
            'valuesIds' => [],
            'values' => $this->getValue(),
            'rangeValue' => null
        ];
    }
}
