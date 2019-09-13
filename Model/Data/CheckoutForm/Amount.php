<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Magento\Framework\DataObject;

class Amount extends DataObject implements AmountInterface
{

    const AMOUNT_FIELD_NAME = 'amount';

    /**
     * @param float $amount
     * @return void
     */
    public function setAmount(float $amount)
    {
        $this->setData(self::AMOUNT_FIELD_NAME, $amount);
    }

    /**
     * @return float
     */
    public function getAmount(): ?float
    {
        return $this->getData(self::AMOUNT_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['amount'])) {
            $this->setAmount($rawData['amount']);
        }
    }
}
