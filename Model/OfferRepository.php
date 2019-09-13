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
use Macopedia\Allegro\Model\Api\ClientException;

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
     * @param OfferInterface $offer
     * @throws ClientException
     * @throws CouldNotSaveException
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
                $offerData = $this->offers->putOffer($offer->getId(), $offerRawData);
            } else {
                $offerData = $this->offers->postOffer($offerRawData);
            }

        } catch (ClientResponseException $e) {
            throw new CouldNotSaveException(__('Could not save offer'), $e);
        }

        if (!isset($offerData['id'])) {
            throw new CouldNotSaveException(__('Could not save offer'));
        }

        $offer->setRawData($offerData);
    }

    /**
     * @param string $offerId
     * @return OfferInterface
     * @throws ClientException
     * @throws NoSuchEntityException
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
