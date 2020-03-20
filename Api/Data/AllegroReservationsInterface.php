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
     * @param CheckoutFormInterface $checkoutForm
     * @return void
     * @throws \Exception
     */
    public function compensateReservation(CheckoutFormInterface $checkoutForm): void;
}
