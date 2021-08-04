<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ShippingRateInterface;

interface ShippingRateRepositoryInterface
{

    /**
     * @return ShippingRateInterface[]
     */
    public function getList(): array;
}
