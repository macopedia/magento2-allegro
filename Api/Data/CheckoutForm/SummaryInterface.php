<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface SummaryInterface
{
    /**
     * @param AmountInterface $totalToPay
     * @return void
     */
    public function setTotalToPay(AmountInterface $totalToPay);

    /**
     * @return AmountInterface
     */
    public function getTotalToPay(): AmountInterface;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
