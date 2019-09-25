<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Consumer\MessageInterface;
use Macopedia\Allegro\Api\ConsumerInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;
use Magento\Framework\MessageQueue\ConnectionLostException;
use Macopedia\Allegro\Model\Api\Credentials;
use Macopedia\Allegro\Api\Data\PublicationCommandInterfaceFactory;
use Macopedia\Allegro\Api\PublicationCommandRepositoryInterface;

/**
 * Consumer class
 */
class Consumer implements ConsumerInterface
{
    /** @var Logger */
    private $logger;

    /** @var ProductRepository  */
    private $productRepository;

    /** @var GetSalableQuantityDataBySku  */
    private $getSalableQuantityDataBySku;

    /** @var Credentials  */
    private $credentials;

    /** @var OfferRepositoryInterface */
    private $offerRepository;

    /** @var PublicationCommandRepositoryInterface */
    private $publicationCommandRepository;

    /** @var PublicationCommandInterfaceFactory */
    private $publicationCommandFactory;

    /** @var Configuration */
    private $config;

    /**
     * Consumer constructor.
     * @param Logger $logger
     * @param ProductRepository $productRepository
     * @param GetSalableQuantityDataBySku $getSalableQuantityDataBySku
     * @param Credentials $credentials
     * @param OfferRepositoryInterface $offerRepository
     * @param PublicationCommandRepositoryInterface $publicationCommandRepository
     * @param PublicationCommandInterfaceFactory $publicationCommandFactory
     * @param Configuration $config
     */
    public function __construct(
        Logger $logger,
        ProductRepository $productRepository,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        Credentials $credentials,
        OfferRepositoryInterface $offerRepository,
        PublicationCommandRepositoryInterface $publicationCommandRepository,
        PublicationCommandInterfaceFactory $publicationCommandFactory,
        Configuration $config
    ) {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->credentials = $credentials;
        $this->offerRepository = $offerRepository;
        $this->publicationCommandRepository = $publicationCommandRepository;
        $this->publicationCommandFactory = $publicationCommandFactory;
        $this->config = $config;
    }

    /**
     * @param MessageInterface $message
     * @throws ClientException
     * @throws ConnectionLostException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function processMessage(MessageInterface $message)
    {
        if (!$this->config->isStockSynchronizationEnabled()) {
            return;
        }

        try {
            $this->credentials->getToken();
        } catch (ClientException $e) {
            throw new ConnectionLostException('Error while receiving token from Allegro');
        }

        $productId = $message->getProductId();

        if (!$productId) {
            $this->logger->warning('Error while receiving product id from observer');
            return;
        }

        try {
            $product = $this->productRepository->getById($productId, false, 0, true);
        } catch (NoSuchEntityException $e) {
            return;
        }

        $allegroOfferId = $product->getData('allegro_offer_id');
        if (!$allegroOfferId) {
            return;
        }

        $productStock = $this->getSalableQuantityDataBySku->execute($product->getSku());
        if (!(isset($productStock[0]) && isset($productStock[0]['qty']))) {
            return;
        }

        try {
            $offer = $this->offerRepository->get($allegroOfferId);
        } catch (NoSuchEntityException $e) {
            return;
        }

        $qty = $productStock[0]['qty'];

        if ($qty > 0) {

            $offer->setQty($qty);
            $this->offerRepository->save($offer);

            if ($offer->getPublicationStatus() == OfferInterface::PUBLICATION_STATUS_ENDED) {
                $this->savePublicationCommand(
                    $offer->getId(),
                    PublicationCommandInterface::ACTION_ACTIVATE
                );
            }

        } else {

            $this->savePublicationCommand(
                $offer->getId(),
                PublicationCommandInterface::ACTION_END
            );

        }

        $this->logger->info(
            sprintf(
                'Quantity of offer with external id %s has been successfully updated',
                $allegroOfferId
            )
        );
    }

    /**
     * @param string $offerId
     * @param string $action
     * @throws ClientException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    private function savePublicationCommand(string $offerId, string $action)
    {
        /** @var PublicationCommandInterface $publicationCommand */
        $publicationCommand = $this->publicationCommandFactory->create();
        $publicationCommand->setOfferId($offerId);
        $publicationCommand->setAction($action);

        $this->publicationCommandRepository->save($publicationCommand);
    }
}
