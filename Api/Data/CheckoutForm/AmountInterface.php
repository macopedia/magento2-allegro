<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface AmountInterface
{

    public function setAmount(float $amount);

    public function getAmount(): ?float;

    public function setRawData(array $rawData);
}
