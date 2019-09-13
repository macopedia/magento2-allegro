<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\PaymentInterface;
use Magento\Framework\DataObject;

class Payment extends DataObject implements PaymentInterface
{

    const TYPE_FIELD_NAME = 'type';
    const PAID_AMOUNT_FIELD_NAME = 'paid_amount';

    /** @var AmountInterfaceFactory */
    private $amountFactory;

    /**
     * Payment constructor.
     * @param AmountInterfaceFactory $amountFactory
     */
    public function __construct(AmountInterfaceFactory $amountFactory)
    {
        $this->amountFactory = $amountFactory;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * @param AmountInterface $paidAmount
     * @return void
     */
    public function setPaidAmount(AmountInterface $paidAmount)
    {
        $this->setData(self::PAID_AMOUNT_FIELD_NAME, $paidAmount);
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * @return AmountInterface
     */
    public function getPaidAmount(): AmountInterface
    {
        return $this->getData(self::PAID_AMOUNT_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['type'])) {
            $this->setType($rawData['type']);
        }
        $this->setPaidAmount($this->mapAmountData($rawData['paidAmount'] ?? []));
    }

    /**
     * @param array $data
     * @return AmountInterface|null
     */
    private function mapAmountData(array $data): AmountInterface
    {
        /** @var AmountInterface $amount */
        $amount = $this->amountFactory->create();
        $amount->setRawData($data);
        return $amount;
    }
}
