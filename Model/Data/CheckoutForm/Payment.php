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
     * @param array $data
     */
    public function __construct(
        AmountInterfaceFactory $amountFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->amountFactory = $amountFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function setPaidAmount(AmountInterface $paidAmount)
    {
        $this->setData(self::PAID_AMOUNT_FIELD_NAME, $paidAmount);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaidAmount(): AmountInterface
    {
        return $this->getData(self::PAID_AMOUNT_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
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
