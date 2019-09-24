<?php

namespace Macopedia\Allegro\Api;

interface ShippingRateRepositoryInterface
{

    /**
     * @return ShippingRateInterface[]
     */
    public function getList(): array;
}
