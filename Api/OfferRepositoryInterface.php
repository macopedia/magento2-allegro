<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface OfferRepositoryInterface
{

    /**
     * @param OfferInterface $offer
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    public function save(OfferInterface $offer);

    /**
     * @param string $offerId
     * @return OfferInterface
     * @throws ClientException
     * @throws NoSuchEntityException
     */
    public function get(string $offerId): OfferInterface;
}
