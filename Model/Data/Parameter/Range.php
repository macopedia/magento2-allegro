<?php


namespace Macopedia\Allegro\Model\Data\Parameter;

use Macopedia\Allegro\Api\Data\Parameter\RangeInterface;
use Macopedia\Allegro\Model\Data\Parameter;
use Magento\Framework\Exception\LocalizedException;

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
    public function getMinValue(): string
    {
        return $this->getData(self::MIN_VALUE_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getMaxValue(): string
    {
        return (string) $this->getData(self::MAX_VALUE_FIELD_NAME);
    }

    public function setValue($value)
    {
        // TODO: Implement setValue() method.
        throw new LocalizedException(__('Parameter type range is not supported'));
    }

    public function getValue()
    {
        // TODO: Implement getValue() method.
        throw new LocalizedException(__('Parameter type range is not supported'));
    }

    public function setRawData(array $rawData)
    {
        // TODO: Implement setRawData() method.
        throw new LocalizedException(__('Parameter type range is not supported'));
    }
}
