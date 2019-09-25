<?php

namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\ValuesIdsInterface;
use Macopedia\Allegro\Model\Data\Parameter;

class ValuesIds extends Parameter implements ValuesIdsInterface
{

    const VALUE_FIELD_NAME = 'value';

    /**
     * @param int[] $value
     * @return void
     */
    public function setValue(array $value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $this->stripEmptyValues($value));
    }

    /**
     * @return int[]
     */
    public function getValue(): array
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }

    /**
     * @return bool
     */
    public function isValueEmpty(): bool
    {
        return count($this->getValue()) < 1;
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        parent::setRawData($rawData);
        $this->setValue($rawData['valuesIds'] ?? []);
    }

    /**
     * @return array
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
