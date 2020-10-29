<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Consumer\MessageInterface;
use Macopedia\Allegro\Api\ConsumerInterface;
use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Api\Data\PublicationCommandInterfaceFactory;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Api\PriceCommandInterface;
use Macopedia\Allegro\Api\PublicationCommandRepositoryInterface;
use Macopedia\Allegro\Api\QuantityCommandInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\Credentials;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\CatalogInventory\Model\Indexer\Stock\Processor;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

/**
 * Consumes messages from Allegro queue
 */
class Consumer implements ConsumerInterface
{
    /**
     * @var QuantityCommandInterface
     */
    protected $quantityCommand;
    /**
     * @var Processor
     */
    protected $indexerProcessor;
    /** @var Logger */
    private $logger;

    /** @var ProductRepository */
    private $productRepository;

    /** @var GetSalableQuantityDataBySku */
    private $getSalableQuantityDataBySku;

    /** @var Credentials */
    private $credentials;

    /** @var OfferRepositoryInterface */
    private $offerRepository;

    /** @var PublicationCommandRepositoryInterface */
    private $publicationCommandRepository;

    /** @var PublicationCommandInterfaceFactory */
    private $publicationCommandFactory;

    /** @var Configuration */
    private $config;

    /** @var PriceCommandInterface */
    private $priceCommand;

    /** @var AllegroPrice */
    protected $allegroPrice;

    /**
     * Consumer constructor.
     * @param Logger $logger
     * @param ProductRepository $productRepository
     * @param GetSalableQuantityDataBySku $getSalableQuantityDataBySku
     * @param Credentials $credentials
     * @param OfferRepositoryInterface $offerRepository
     * @param PublicationCommandRepositoryInterface $publicationCommandRepository
     * @param PublicationCommandInterfaceFactory $publicationCommandFactory
     * @param QuantityCommandInterface $quantityCommand
     * @param Configuration $config
     * @param Processor $indexerProcessor
     * @param PriceCommandInterface $priceCommand
     * @param AllegroPrice $allegroPrice
     */
    public function __construct(
        Logger $logger,
        ProductRepository $productRepository,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        Credentials $credentials,
        OfferRepositoryInterface $offerRepository,
        PublicationCommandRepositoryInterface $publicationCommandRepository,
        PublicationCommandInterfaceFactory $publicationCommandFactory,
        QuantityCommandInterface $quantityCommand,
        Configuration $config,
        Processor $indexerProcessor,
        PriceCommandInterface $priceCommand,
        AllegroPrice $allegroPrice
    ) {
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->credentials = $credentials;
        $this->offerRepository = $offerRepository;
        $this->publicationCommandRepository = $publicationCommandRepository;
        $this->publicationCommandFactory = $publicationCommandFactory;
        $this->config = $config;
        $this->quantityCommand = $quantityCommand;
        $this->indexerProcessor = $indexerProcessor;
        $this->priceCommand = $priceCommand;
        $this->allegroPrice = $allegroPrice;
    }

    /**
     * @param MessageInterface $message
     */
    public function processMessage(MessageInterface $message)
    {
        $productId = $message->getProductId();
        if (!$productId) {
            $this->logger->warning('Error while receiving product id from observer');
            return;
        }

        if (!$this->config->isStockSynchronizationEnabled()) {
            return;
        }

        try {
            if ($product = $this->productRepository->getMinProductWithAllegro($productId)) {
                $allegroOfferId = $product->getData('allegro_offer_id');
                if (!$allegroOfferId) {
                    $this->logger->debug('Error while receiving product id from observer');
                    return;
                }
                // refresh stock index to have current stock data
                try {
                    $this->indexerProcessor->reindexList([$product->getId()], true);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage(), $exception->getTrace());
                }

                $this->updateOfferPrice($product, $allegroOfferId);

                $offer = $this->offerRepository->get($allegroOfferId);
                $productStock = $this->getSalableQuantityDataBySku->execute($product->getSku());
                if (!isset($productStock[0]['qty'])) {
                    return;
                }

                $qty = $productStock[0]['qty'];
                if ($qty > 0) {
                    $this->quantityCommand->change($allegroOfferId, $qty);
                    if (!$offer->isDraft()) {
                        $this->savePublicationCommand(
                            $allegroOfferId,
                            PublicationCommandInterface::ACTION_ACTIVATE
                        );
                    }
                } else {
                    $this->savePublicationCommand(
                        $allegroOfferId,
                        PublicationCommandInterface::ACTION_END
                    );

                }

                $this->logger->info(
                    sprintf(
                        'Quantity and price of offer with external id %s have been successfully updated',
                        $allegroOfferId
                    )
                );
            }

        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            return;
        }
    }

    /**
     * @param string $offerId
     * @param string $action
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    private function savePublicationCommand(string $offerId, string $action)
    {
        $publicationCommand = $this->publicationCommandFactory->create();
        $publicationCommand->setOfferId($offerId);
        $publicationCommand->setAction($action);

        $this->publicationCommandRepository->save($publicationCommand);
    }

    /**
     * @param ProductInterface $product
     * @param string $offerId
     * @throws AllegroPriceGettingException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function updateOfferPrice(ProductInterface $product, string $offerId)
    {
        if (!$this->config->isPricePolicyEnabled()) {
            return;
        }

        $price = $this->allegroPrice->getByProductId($product->getId());

        if ($price > 0) {
            $this->priceCommand->change($offerId, $price);
        }
    }
}
