<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterfaceFactory;
use Macopedia\Allegro\Api\OrderLogRepositoryInterface;
use Macopedia\Allegro\Model\OrderLogRepository;
use Macopedia\Allegro\Api\OrderRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Creator;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Macopedia\Allegro\Model\Configuration;
use Magento\Framework\App\ResourceConnection;

class Processor
{
    /** @var Creator */
    private $creator;

    /** @var Logger */
    private $logger;

    /** @var OrderLogInterfaceFactory */
    private $orderLogFactory;

    /** @var OrderLogRepositoryInterface */
    private $orderLogRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var DateTime */
    private $date;

    /** @var Configuration */
    private $configuration;

    /** @var AllegroReservation */
    private $allegroReservation;

    /** @var ResourceConnection */
    private $resource;

    /**
     * Processor constructor.
     * @param Creator $creator
     * @param Logger $logger
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderLogRepositoryInterface $orderLogRepository
     * @param OrderLogInterfaceFactory $orderLogFactory
     * @param DateTime $date
     * @param Configuration $configuration
     * @param AllegroReservation $allegroReservation
     * @param ResourceConnection $resource
     */
    public function __construct(
        Creator $creator,
        Logger $logger,
        OrderRepositoryInterface $orderRepository,
        OrderLogRepositoryInterface $orderLogRepository,
        OrderLogInterfaceFactory $orderLogFactory,
        DateTime $date,
        Configuration $configuration,
        AllegroReservation $allegroReservation,
        ResourceConnection $resource
    ) {
        $this->creator = $creator;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->orderLogRepository = $orderLogRepository;
        $this->orderLogFactory = $orderLogFactory;
        $this->date = $date;
        $this->configuration = $configuration;
        $this->allegroReservation = $allegroReservation;
        $this->resource = $resource;
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    public function processOrder(CheckoutFormInterface $checkoutForm): void
    {
        $connection = $this->resource->getConnection();
        try {
            $connection->beginTransaction();

            if ($checkoutForm->getStatus() === Status::ALLEGRO_READY_FOR_PROCESSING) {
                if (!$this->tryToGetOrder($checkoutForm->getId())) {
                    $this->allegroReservation->compensateReservation($checkoutForm);
                    $this->tryCreateOrder($checkoutForm);
                }
            } elseif ($checkoutForm->getStatus() === Status::ALLEGRO_CANCELLED) {
                $this->allegroReservation->compensateReservation($checkoutForm);
            } else {
                $this->allegroReservation->placeReservation($checkoutForm);
            }

            $this->removeErrorLogIfExist($checkoutForm);
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            $this->addOrderWithErrorToTable($checkoutForm, $e);
            throw $e;
        }
    }

    /**
     * @param $id
     * @return OrderInterface|null
     */
    protected function tryToGetOrder($id): ?OrderInterface
    {
        try {
            return $this->orderRepository->getByExternalId($id);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @param \Exception $e
     * @throws \Exception
     */
    private function addOrderWithErrorToTable(CheckoutFormInterface $checkoutForm, \Exception $e): void
    {
        $checkoutFormId = $checkoutForm->getId();

        $date = $this->date->gmtDate();

        try {
            $orderLog = $this->orderLogRepository->getByCheckoutFormId($checkoutFormId);
            $orderLog->setNumberOfTries($orderLog->getNumberOfTries() + 1);
        } catch (NoSuchEntityException $noSuchEntityException) {
            $orderLog = $this->orderLogFactory->create();
            $orderLog->setDateOfFirstTry($date);
            $orderLog->setNumberOfTries(1);
        }

        $orderLog->setCheckoutFormId($checkoutFormId);
        $orderLog->setDateOfLastTry($date);

        $reason = [$e->getMessage()];
        while ($e->getPrevious()) {
            $e = $e->getPrevious();
            $reason[] = $e->getMessage();
        }

        $orderLog->setReason(implode("\n", $reason));

        try {
            $this->orderLogRepository->save($orderLog);
        } catch (CouldNotSaveException $e) {
            throw new OrderProcessingException(
                "Error while adding order with id [{$checkoutFormId}] to allegro_orders_with_errors table",
                1589540670,
                $e
            );
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    private function tryCreateOrder(CheckoutFormInterface $checkoutForm): void
    {
        $checkoutFormId = $checkoutForm->getId();
        try {
            $this->creator->execute($checkoutForm);
            $this->logger->info("Order with id [$checkoutFormId] has been successfully created");
        } catch (\Exception $e) {
            throw new OrderProcessingException(
                "Error while creating order with id [{$checkoutFormId}]",
                1589540684,
                $e
            );
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    private function removeErrorLogIfExist(CheckoutFormInterface $checkoutForm): void
    {
        $checkoutFormId = $checkoutForm->getId();
        try {
            $this->orderLogRepository->deleteByCheckoutFormId($checkoutFormId);
        } catch (CouldNotDeleteException $e) {
            throw new OrderProcessingException(
                "Error while deleting order with id [{$checkoutFormId}] from allegro_orders_with_errors table",
                1589540677,
                $e
            );
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @return bool
     */
    public function validateCheckoutFormBoughtAtDate(CheckoutFormInterface $checkoutForm): bool
    {
        foreach ($checkoutForm->getLineItems() as $lineItem) {
            if ($lineItem->getBoughtAt() < $this->configuration->getInitializationTime()) {
                return false;
            }
        }

        return true;
    }
}
