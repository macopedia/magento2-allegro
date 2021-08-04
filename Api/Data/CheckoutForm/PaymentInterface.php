<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface PaymentInterface
{
    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * @param AmountInterface $paidAmount
     * @return void
     */
    public function setPaidAmount(AmountInterface $paidAmount);

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @return AmountInterface
     */
    public function getPaidAmount(): AmountInterface;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
