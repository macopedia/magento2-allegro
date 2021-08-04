<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface LineItemInterface
{
    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param float $qty
     * @return void
     */
    public function setQty(float $qty);

    /**
     * @param AmountInterface $price
     * @return void
     */
    public function setPrice(AmountInterface $price);

    /**
     * @param string $offerId
     * @return void
     */
    public function setOfferId(string $offerId);

    /**
     * @param int $time
     * @return void
     */
    public function setBoughtAt(int $time);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return float|null
     */
    public function getQty(): ?float;

    /**
     * @return AmountInterface
     */
    public function getPrice(): AmountInterface;

    /**
     * @return string|null
     */
    public function getOfferId(): ?string;

    /**
     * @return int
     */
    public function getBoughtAt(): int;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
