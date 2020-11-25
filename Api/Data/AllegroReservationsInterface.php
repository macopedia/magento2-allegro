<?php

namespace Macopedia\Allegro\Api\Data;

interface AllegroReservationsInterface
{
    /**
     * @param CheckoutFormInterface $checkoutForm
     * @return void
     * @throws \Exception
     */
    public function placeReservation(CheckoutFormInterface $checkoutForm): void;

    /**
     * @param string $checkoutFormId
     * @return void
     * @throws \Exception
     */
    public function compensateReservation(string $checkoutFormId): void;
}
