<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\AllegroReservationsInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory;
use Magento\InventorySalesApi\Api\Data\SalesEventInterface;
use Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory;
use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory;
use Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Macopedia\Allegro\Model\ReservationRepository;
use Magento\Framework\App\ResourceConnection;
use Macopedia\Allegro\Model\Configuration;

class AllegroReservation implements AllegroReservationsInterface
{
    /**#@+
     * Constants for event types
     */
    const ALLEGRO_EVENT_RESERVATION_PLACED = 'allegro_reservation_placed';
    const ALLEGRO_EVENT_RESERVATION_COMPENSATED = 'allegro_reservation_compensated';
    /**#@-*/

    /**#@+
     * Constants for event object types
     */
    const OBJECT_TYPE_ALLEGRO = 'allegro_checkout_form';
    /**#@-*/

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ItemToSellInterfaceFactory */
    private $itemsToSellFactory;

    /** @var SalesEventInterfaceFactory */
    private $salesEventFactory;

    /** @var SalesChannelInterfaceFactory */
    private $salesChannelFactory;

    /** @var PlaceReservationsForSalesEventInterface */
    private $placeReservationsForSalesEvent;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var ReservationRepository */
    private $reservationRepository;

    /** @var ResourceConnection */
    private $resource;

    /** @var Configuration */
    private $configuration;

    /**
     * AllegroReservation constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param ItemToSellInterfaceFactory $itemsToSellFactory
     * @param SalesEventInterfaceFactory $salesEventFactory
     * @param SalesChannelInterfaceFactory $salesChannelFactory
     * @param PlaceReservationsForSalesEventInterface $placeReservationsForSalesEvent
     * @param StoreManagerInterface $storeManager
     * @param ReservationRepository $reservationRepository
     * @param ResourceConnection $resource
     * @param Configuration $configuration
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ItemToSellInterfaceFactory $itemsToSellFactory,
        SalesEventInterfaceFactory $salesEventFactory,
        SalesChannelInterfaceFactory $salesChannelFactory,
        PlaceReservationsForSalesEventInterface $placeReservationsForSalesEvent,
        StoreManagerInterface $storeManager,
        ReservationRepository $reservationRepository,
        ResourceConnection $resource,
        Configuration $configuration
    ) {
        $this->productRepository = $productRepository;
        $this->itemsToSellFactory = $itemsToSellFactory;
        $this->salesEventFactory = $salesEventFactory;
        $this->salesChannelFactory = $salesChannelFactory;
        $this->placeReservationsForSalesEvent = $placeReservationsForSalesEvent;
        $this->storeManager = $storeManager;
        $this->reservationRepository = $reservationRepository;
        $this->resource = $resource;
        $this->configuration = $configuration;
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    public function placeReservation(CheckoutFormInterface $checkoutForm): void
    {
        try {
            $this->placeReservationsForSalesEvent->execute(
                $this->getItemsToSell($checkoutForm, false),
                $this->getSalesChannel(),
                $this->getSalesEvent(self::ALLEGRO_EVENT_RESERVATION_PLACED, $checkoutForm->getId()));
        } catch (\Exception $e) {
            throw new \Exception(
                "Error while placing reservation for order with id [{$checkoutForm->getId()}]",
                0,
                $e
            );
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    public function compensateReservation(CheckoutFormInterface $checkoutForm): void
    {
        try {
            $this->placeReservationsForSalesEvent->execute(
                $this->getItemsToSell($checkoutForm, true),
                $this->getSalesChannel(),
                $this->getSalesEvent(self::ALLEGRO_EVENT_RESERVATION_COMPENSATED, $checkoutForm->getId())
            );
        } catch (\Exception $e) {
            throw new \Exception(
                "Error while compensating reservation for order with id [{$checkoutForm->getId()}]",
                0,
                $e
            );
        }
    }

    /**
     * @param string $type
     * @param string $checkoutFormId
     * @return SalesEventInterface
     */
    private function getSalesEvent(string $type, string $checkoutFormId): SalesEventInterface
    {
        /** @var SalesEventInterface $salesEvent */
        return $salesEvent = $this->salesEventFactory->create([
            'type' => $type,
            'objectType' => self::OBJECT_TYPE_ALLEGRO,
            'objectId' => $checkoutFormId
        ]);
    }

    /**
     * @return SalesChannelInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getSalesChannel(): SalesChannelInterface
    {
        $storeId = $this->configuration->getStoreId();
        if (!$storeId) {
            $storeId = Store::DEFAULT_STORE_ID;
        }

        $websiteCode = $this->storeManager->getWebsite($this->storeManager->getStore($storeId)->getWebsiteId())->getCode();

        return $salesChannel = $this->salesChannelFactory->create([
            'data' => [
                'type' => SalesChannelInterface::TYPE_WEBSITE,
                'code' => $websiteCode
            ]
        ]);
    }

    /**
     * @param string $checkoutFormId
     * @return array
     * @throws NoSuchEntityException
     */
    private function getProductsSku(string $checkoutFormId): array
    {
        $reservations = $this->reservationRepository->getByCheckoutFormId($checkoutFormId);

        $connection = $this->resource->getConnection();

        $originalReservations = $connection->getTableName('inventory_reservation');

        $reservationsIds = [];

        foreach ($reservations as $reservation) {
            array_push($reservationsIds, $reservation->getReservationId());
        }

        $query = $connection
            ->select()
            ->from($originalReservations, ['sku'])
            ->where('reservation_id IN (?)', $reservationsIds);

        return $connection->fetchCol($query);
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @param bool $compensate
     * @return array
     * @throws CreatorItemsException
     * @throws NoSuchEntityException
     */
    private function getItemsToSell(CheckoutFormInterface $checkoutForm, bool $compensate = false): array
    {
        $itemsToSell = [];
        $productsSku = $this->getProductsSku($checkoutForm->getId());

        /** @var LineItemInterface $lineItem */
        foreach ($checkoutForm->getLineItems() as $lineItem) {
            $offerId = (int)$lineItem->getOfferId();
            try {
                $product = $this->productRepository->getByAllegroOfferId($offerId);
                $sku = $product->getSku();
            } catch (NoSuchEntityException $e) {
                throw new CreatorItemsException(__('Product for requested offer id "%1" does not exist', $offerId));
            }

            if (in_array($sku, $productsSku) === $compensate) {
                $itemsToSell[] = $this->itemsToSellFactory->create([
                    'sku' => $sku,
                    'qty' => $compensate ? (float)$lineItem->getQty() : -(float)$lineItem->getQty()
                ]);
            }
        }

        return $itemsToSell;
    }
}
