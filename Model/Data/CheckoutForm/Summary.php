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
     */
    public function __construct(AmountInterfaceFactory $amountFactory)
    {
        $this->amountFactory = $amountFactory;
    }

    /**
     * @param AmountInterface $totalToPay
     */
    public function setTotalToPay(AmountInterface $totalToPay)
    {
        $this->setData(self::TOTAL_TO_PAY_FIELD_NAME, $totalToPay);
    }

    /**
     * @return AmountInterface
     */
    public function getTotalToPay(): AmountInterface
    {
        return $this->getData(self::TOTAL_TO_PAY_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        $this->setTotalToPay($this->mapAmountData($rawData['totalToPay'] ?? []));
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
