<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\QuantityCommandInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;

class QuantityCommand implements QuantityCommandInterface
{

    /** @var Offers */
    private $offers;

    /**
     * PublicationCommandRepository constructor.
     * @param Offers $offers
     */
    public function __construct(Offers $offers)
    {
        $this->offers = $offers;
    }

    /**
     * {@inheritDoc}
     */
    public function change($offerId, $qty)
    {
        return $this->offers->changeQuantity($offerId, $qty);
    }
}
