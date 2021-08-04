<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Magento\Framework\DataObject;

class Amount extends DataObject implements AmountInterface
{
    const AMOUNT_FIELD_NAME = 'amount';

    /**
     * {@inheritDoc}
     */
    public function setAmount(float $amount)
    {
        $this->setData(self::AMOUNT_FIELD_NAME, $amount);
    }

    /**
     * {@inheritDoc}
     */
    public function getAmount(): ?float
    {
        return $this->getData(self::AMOUNT_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['amount'])) {
            $this->setAmount($rawData['amount']);
        }
    }
}
