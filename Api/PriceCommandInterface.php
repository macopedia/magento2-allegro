<?php

namespace Macopedia\Allegro\Api;

interface PriceCommandInterface
{
    /**
     * @param string $offerId
     * @param float $price
     * @return array
     */
    public function change(string $offerId, float $price);
}
