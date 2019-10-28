<?php

namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\RangeInterface;
use Macopedia\Allegro\Model\Data\Parameter;

class Range extends Parameter implements RangeInterface
{

    const MIN_VALUE_FIELD_NAME = 'min_value';
    const MAX_VALUE_FIELD_NAME = 'max_value';

    /**
     * @param string $minValue
     * @return void
     */
    public function setMinValue(string $minValue)
    {
        $this->setData(self::MIN_VALUE_FIELD_NAME, $minValue);
    }

    /**
     * @param string $maxValue
     * @return void
     */
    public function setMaxValue(string $maxValue)
    {
        $this->setData(self::MAX_VALUE_FIELD_NAME, $maxValue);
    }

    /**
     * @return string
     */
    public function getMinValue(): ?string
    {
        return $this->getData(self::MIN_VALUE_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getMaxValue(): ?string
    {
        return (string) $this->getData(self::MAX_VALUE_FIELD_NAME);
    }

    /**
     * @return bool
     */
    public function isValueEmpty(): bool
    {
        return $this->getMinValue() === '' || $this->getMinValue() === null
            || $this->getMaxValue() === '' || $this->getMaxValue() === null;
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        parent::setRawData($rawData);
        if (isset($rawData['rangeValue']['from'])) {
            $this->setMinValue($rawData['rangeValue']['from']);
        }
        if (isset($rawData['rangeValue']['to'])) {
            $this->setMaxValue($rawData['rangeValue']['to']);
        }
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'id' => $this->getId(),
            'valuesIds' => [],
            'values' => [],
            'rangeValue' => [
                'from' => $this->getMinValue(),
                'to' => $this->getMaxValue()
            ]
        ];
    }
}
