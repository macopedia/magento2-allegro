<?php


namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\ValuesInterface;
use Macopedia\Allegro\Model\Data\Parameter;

class Values extends Parameter implements ValuesInterface
{

    const VALUE_FIELD_NAME = 'value';

    /**
     * @param string[] $value
     * @return void
     */
    public function setValue($value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $value);
    }

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        $this->setValue($rawData['values'] ?? []);
    }

    /**
     * @return array
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
