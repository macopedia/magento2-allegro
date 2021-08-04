<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Delivery;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\CostInterface;
use Magento\Framework\DataObject;

class Cost extends DataObject implements CostInterface
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
