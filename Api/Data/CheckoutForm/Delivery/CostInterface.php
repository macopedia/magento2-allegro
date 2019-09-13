<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

interface CostInterface
{

    public function setAmount(float $amount);

    public function getAmount(): ?float;

    public function setRawData(array $rawData);
}
