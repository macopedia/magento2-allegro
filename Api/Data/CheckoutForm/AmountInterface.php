<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface AmountInterface
{
    /**
     * @param float $amount
     * @return void
     */
    public function setAmount(float $amount);

    /**
     * @return float|null
     */
    public function getAmount(): ?float;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
