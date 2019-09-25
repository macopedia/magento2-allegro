<?php


namespace Macopedia\Allegro\Model\Data\ParameterDefinition;

use Macopedia\Allegro\Api\Data\ParameterDefinition\DictionaryItemInterface;
use Magento\Framework\DataObject;

class DictionaryItem extends DataObject implements DictionaryItemInterface
{

    const VALUE_FIELD_NAME = 'value';
    const LABEL_FIELD_NAME = 'label';

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->setData(self::VALUE_FIELD_NAME, $value);
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->setData(self::LABEL_FIELD_NAME, $label);
    }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->getData(self::VALUE_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getLabel(): ?string
    {
        return $this->getData(self::LABEL_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setValue($rawData['id']);
        }
        if (isset($rawData['value'])) {
            $this->setLabel($rawData['value']);
        }
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'id' => $this->getValue(),
            'value' => $this->getLabel()
        ];
    }
}
