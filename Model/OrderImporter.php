<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Api\Data\EventInterface;
use Macopedia\Allegro\Api\EventRepositoryInterface;
use Macopedia\Allegro\Api\OrderRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\OrderImporter\Creator;
use Macopedia\Allegro\Model\OrderImporter\CreatorItemsException;
use Macopedia\Allegro\Model\OrderImporter\Updater;
use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\FlagManager;

/**
 * Class responsible for handling events fetched from Allegro API
 */
class OrderImporter
{
    const LAST_ORDER_ID_FLAG_KEY = 'macopedia_allegro_last_order_id';
    const BOUGHT_TYPE = 'BOUGHT';
    const STATUS_FILLED_IN = 'FILLED_IN';
    const STATUS_BOUGHT = 'BOUGHT';

    private $errorsCount = 0;
    private $createdIds = [];
    private $updatedIds = [];

    /** @var EventRepositoryInterface */
    private $eventRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var CheckoutFormRepositoryInterface */
    private $checkoutFormRepository;

    /** @var FlagManager */
    private $flagManager;

    /** @var Creator */
    private $creator;

    /** @var Updater */
    private $updater;

    /** @var Logger */
    private $logger;

    /**
     * OrderImporter constructor.
     * @param EventRepositoryInterface $eventRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param FlagManager $flagManager
     * @param Creator $creator
     * @param Updater $updater
     * @param Logger $logger
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        OrderRepositoryInterface $orderRepository,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        FlagManager $flagManager,
        Creator $creator,
        Updater $updater,
        Logger $logger
    ) {
        $this->eventRepository = $eventRepository;
        $this->orderRepository = $orderRepository;
        $this->checkoutFormRepository = $checkoutFormRepository;
        $this->flagManager = $flagManager;
        $this->creator = $creator;
        $this->updater = $updater;
        $this->logger = $logger;
    }

    /**
     * @return Info
     */
    public function execute()
    {
        try {
            $lastEventId = $this->flagManager->getFlagData(self::LAST_ORDER_ID_FLAG_KEY);
            if ($lastEventId) {
                $events = $this->eventRepository->getListFrom($lastEventId);
            } else {
                $events = $this->eventRepository->getList();
            }
        } catch (\Exception $e) {
            $this->logger->error('Wrong response received while fetching events data');
            $this->errorsCount++;
            return $this->prepareInfoResponse();
        }

        if (count($events) < 1) {
            return $this->prepareInfoResponse();
        }

        try {

            /** @var EventInterface $event */
            foreach ($events as $event) {
                $this->executeEvent($event);
                $lastEventId = $event->getId();
                $this->flagManager->saveFlag(self::LAST_ORDER_ID_FLAG_KEY, $lastEventId);
            }

        } catch (\Exception $e) {
            $this->errorsCount++;
            $lastEvent = $event ?? reset($events);
            $this->logger->exception(
                $e,
                "Error while creating/updating order for checkout form with id [{$lastEvent->getCheckoutFormId()}]"
            );
        }

        return $this->prepareInfoResponse();
    }

    /**
     * @param EventInterface $event
     * @throws ClientException
     * @throws NoSuchEntityException
     * @throws OrderImporter\BillingAddressIdException
     * @throws OrderImporter\ShippingAddressIdException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function executeEvent(EventInterface $event)
    {
        if ($event->getType() !== 'READY_FOR_PROCESSING') {
            return;
        }

        $checkoutFormId = $event->getCheckoutFormId();
        $checkoutForm = $this->checkoutFormRepository->get($checkoutFormId);

        try {
            $order = $this->orderRepository->getByExternalId($checkoutFormId);
        } catch (NoSuchEntityException $e) {

            try {
                $orderId = $this->creator->execute($checkoutForm);
                $this->logger->info("Order with id [$checkoutFormId] has been successfully created");
                $this->createdIds[$orderId] = $checkoutFormId;
            } catch (CreatorItemsException $e) {
                $this->errorsCount++;
                $this->logger->exception(
                    $e,
                    "Error while creating order for checkout form with id [{$checkoutFormId}]"
                );
            }
            return;

        }

        $this->updater->execute($order, $checkoutForm);

        $this->logger->info("Order with id [$checkoutFormId] has been successfully updated");
        if (!isset($createdIds[$order->getEntityId()])) {
            $this->updatedIds[$order->getEntityId()] = $checkoutFormId;
        }
    }

    /**
     * @return Info
     */
    private function prepareInfoResponse()
    {
        $info = new OrderImporter\Info();

        return $info
            ->setImportedCount(count($this->createdIds))
            ->setUpdatedCount(count($this->updatedIds))
            ->setErrorsCount($this->errorsCount);
    }
}
