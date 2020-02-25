<?php

namespace Macopedia\Allegro\Api\Data;

use Magento\InventoryReservationsApi\Model\ReservationInterface as OriginalReservationInterface;

interface ReservationInterface
{
    /**
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getReservationId(): int;

    /**
     * @return string
     */
    public function getCheckoutFormId(): string;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param int $reservationId
     * @return void
     */
    public function setReservationId(int $reservationId): void;

    /**
     * @param string $checkoutFormId
     * @return void
     */
    public function setCheckoutFormId(string $checkoutFormId): void;

    /**
     * @param string $sku
     */
    public function setSku(string $sku): void;
}
