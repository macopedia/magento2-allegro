<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\SummaryInterface;
use Magento\Framework\DataObject;

class Summary extends DataObject implements SummaryInterface
{
    const TOTAL_TO_PAY_FIELD_NAME = 'total_to_pay';

    /** @var AmountInterfaceFactory */
    private $amountFactory;

    /**
     * Summary constructor.
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
    public function setTotalToPay(AmountInterface $totalToPay)
    {
        $this->setData(self::TOTAL_TO_PAY_FIELD_NAME, $totalToPay);
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalToPay(): AmountInterface
    {
        return $this->getData(self::TOTAL_TO_PAY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        $this->setTotalToPay($this->mapAmountData($rawData['totalToPay'] ?? []));
    }

    /**
     * @param array $data
     * @return AmountInterface
     */
    private function mapAmountData(array $data): AmountInterface
    {
        /** @var AmountInterface $amount */
        $amount = $this->amountFactory->create();
        $amount->setRawData($data);
        return $amount;
    }
}
