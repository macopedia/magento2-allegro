<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\PriceCommandInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;

class PriceCommand implements PriceCommandInterface
{

    /** @var Offers */
    private $offers;

    /**
     * PriceCommand constructor.
     * @param Offers $offers
     */
    public function __construct(Offers $offers)
    {
        $this->offers = $offers;
    }

    /**
     * {@inheritDoc}
     */
    public function change(string $offerId, float $price)
    {
        return $this->offers->changePrice($offerId, $price);
    }
}
