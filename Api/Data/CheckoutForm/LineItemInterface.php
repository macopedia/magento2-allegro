<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface LineItemInterface
{

    public function setId(string $id);
    public function setQty(float $qty);
    public function setPrice(AmountInterface $price);
    public function setOfferId(string $offerId);

    public function getId(): ?string;
    public function getQty(): ?float;
    public function getPrice(): AmountInterface;
    public function getOfferId(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
