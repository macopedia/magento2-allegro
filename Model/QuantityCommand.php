<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Api\PublicationCommandRepositoryInterface;
use Macopedia\Allegro\Api\QuantityCommandInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Framework\Exception\CouldNotSaveException;
use Macopedia\Allegro\Model\Api\ClientException;

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
     * @param $offerId
     * @param $qty
     * @return array
     * @throws Api\ClientResponseErrorException
     * @throws ClientException
     * @throws ClientResponseException
     */
    public function change($offerId, $qty)
    {
        return $this->offers->changeQuantity($offerId, $qty);
    }
}
