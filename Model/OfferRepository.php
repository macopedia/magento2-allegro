<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\OfferInterfaceFactory;
use Macopedia\Allegro\Api\ImageRepositoryInterface;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Macopedia\Allegro\Model\Api\ClientResponseException;

class OfferRepository implements OfferRepositoryInterface
{
    /** @var Offers */
    private $offers;

    /** @var OfferInterfaceFactory */
    private $offerFactory;

    /** @var ImageRepositoryInterface */
    private $imageRepository;

    /**
     * OfferRepository constructor.
     * @param Offers $offers
     * @param OfferInterfaceFactory $offerFactory
     * @param ImageRepositoryInterface $imageRepository
     */
    public function __construct(
        Offers $offers,
        OfferInterfaceFactory $offerFactory,
        ImageRepositoryInterface $imageRepository
    ) {
        $this->offers = $offers;
        $this->offerFactory = $offerFactory;
        $this->imageRepository = $imageRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function save(OfferInterface $offer)
    {
        foreach ($offer->getImages() as $image) {
            if ($image->getStatus() == ImageInterface::STATUS_UPLOADED) {
                continue;
            }
            $this->imageRepository->save($image);
        }

        $offerRawData = $offer->getRawData();

        try {

            if ($offer->getId()) {
                $offerRawData = $this->offers->putOffer($offer->getId(), $offerRawData);
            } else {
                $offerRawData = $this->offers->postOffer($offerRawData);
            }

        } catch (ClientResponseException $e) {
            throw new CouldNotSaveException(__('Could not save offer Reason: %1', $e->getMessage()), $e);
        }

        if (!isset($offerRawData['id'])) {
            throw new CouldNotSaveException(__('Could not save offer'));
        }

        $offer->setRawData($offerRawData);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $offerId): OfferInterface
    {
        try {

            $offerData = $this->offers->get($offerId);

        } catch (ClientResponseException $e) {
            throw new NoSuchEntityException(__('Requested offer with id "%1" does not exist', $offerId), $e);
        }

        /** @var OfferInterface $offer */
        $offer = $this->offerFactory->create();
        $offer->setRawData($offerData);
        return $offer;
    }
}
