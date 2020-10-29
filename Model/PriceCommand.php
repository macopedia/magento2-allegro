<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\PriceCommandInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Macopedia\Allegro\Model\Api\ClientException;

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
     * @param string $offerId
     * @param float $price
     * @return array
     * @throws Api\ClientResponseErrorException
     * @throws ClientException
     * @throws ClientResponseException
     */
    public function change(string $offerId, float $price)
    {
        return $this->offers->changePrice($offerId, $price);
    }
}
