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
    public function setValue($value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $value);
    }

    /**
     * @return int[]
     */
    public function getValue(): array
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }

    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        $this->setValue($rawData['valuesIds'] ?? []);
    }

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
