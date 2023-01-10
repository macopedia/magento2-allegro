<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Api\PublicationCommandRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Framework\Exception\CouldNotSaveException;

class PublicationCommandRepository implements PublicationCommandRepositoryInterface
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
    public function save(PublicationCommandInterface $publication)
    {
        try {

            $this->offers->changeOfferStatus($publication->getRawData());

        } catch (ClientResponseException $e) {
            throw new CouldNotSaveException(__('Could not send publication request Reason: %1', $e->getMessage()), $e);
        }
    }
}
