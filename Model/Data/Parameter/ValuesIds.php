<?php

namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\ValuesIdsInterface;
use Macopedia\Allegro\Model\Data\Parameter;

class ValuesIds extends Parameter implements ValuesIdsInterface
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
    public function isValueEmpty(): bool
    {
        return count($this->getValue()) < 1;
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        parent::setRawData($rawData);
        $this->setValue($rawData['valuesIds'] ?? []);
    }

    /**
     * {@inheritDoc}
     */
    public function getRawData(): array
    {
        return [
            'id' => $this->getId(),
            'valuesIds' => $this->getValue(),
            'value' => [],
            'rangeValue' => null
        ];
    }
}
