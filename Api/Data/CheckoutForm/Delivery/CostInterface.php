<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

interface CostInterface
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
