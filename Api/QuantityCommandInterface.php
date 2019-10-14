<?php

namespace Macopedia\Allegro\Api;

interface QuantityCommandInterface
{
    /**
     * @param $offerId
     * @param $qty
     * @return array
     */
    public function change($offerId, $qty);
}
