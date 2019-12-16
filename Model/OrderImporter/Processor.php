<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterfaceFactory;
use Macopedia\Allegro\Api\OrderLogRepositoryInterface;
use Macopedia\Allegro\Model\OrderLogRepository;
use Macopedia\Allegro\Api\OrderRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Creator;
use Macopedia\Allegro\Model\OrderImporter\Updater;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Processor
{

    /** @var Creator */
    private $creator;

    /** @var Updater */
    private $updater;

    /** @var Logger */
    private $logger;

    /** @var OrderLogInterfaceFactory */
    private $orderLogFactory;

    /** @var OrderLogRepositoryInterface */
    private $orderLogRepository;

    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var DateTime  */
    private $date;

    /**
     * Processor constructor.
     * @param Creator $creator
     * @param Updater $updater
     * @param Logger $logger
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderLogRepositoryInterface $orderLogRepository
     * @param OrderLogInterfaceFactory $orderLogFactory
     * @param DateTime $date
     */
    public function __construct(
        Creator $creator,
        Updater $updater,
        Logger $logger,
        OrderRepositoryInterface $orderRepository,
        OrderLogRepositoryInterface $orderLogRepository,
        OrderLogInterfaceFactory $orderLogFactory,
        DateTime $date
    )
    {
        $this->creator = $creator;
        $this->updater = $updater;
        $this->logger = $logger;
        $this->orderRepository = $orderRepository;
        $this->orderLogRepository = $orderLogRepository;
        $this->orderLogFactory = $orderLogFactory;
        $this->date = $date;
    }


    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    public function processOrder(CheckoutFormInterface $checkoutForm)
    {
        $order = null;

        try {
            $order = $this->orderRepository->getByExternalId($checkoutForm->getId());
        } catch (NoSuchEntityException $e) {
        }

        try {

            if ($order !== null) {
                $this->tryUpdateOrder($order, $checkoutForm);
            } else {
                $this->tryCreateOrder($checkoutForm);
            }

            $this->removeErrorLogIfExist($checkoutForm);

        } catch (\Exception $e) {
            $this->addOrderWithErrorToTable($checkoutForm, $e);
            throw $e;
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @param \Exception $e
     * @throws CouldNotSaveException
     */
    private function addOrderWithErrorToTable(CheckoutFormInterface $checkoutForm, \Exception $e)
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
        $orderLog->setReason($e->getMessage());

        try {
            $this->orderLogRepository->save($orderLog);
        } catch (CouldNotSaveException $couldNotSaveException) {
            $this->logger->exception(
                $couldNotSaveException,
                "Error while adding order with id [{$checkoutFormId}] to allegro_orders_with_errors table"
            );
            throw $couldNotSaveException;
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    private function tryCreateOrder(CheckoutFormInterface $checkoutForm)
    {
        $checkoutFormId = $checkoutForm->getId();
        try {
            $this->creator->execute($checkoutForm);
            $this->logger->info("Order with id [$checkoutFormId] has been successfully created");
        } catch (\Exception $e) {
            $this->logger->exception(
                $e,
                "Error while creating order with id [{$checkoutFormId}]"
            );
            throw $e;
        }
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     * @throws \Exception
     */
    private function tryUpdateOrder(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $checkoutFormId = $checkoutForm->getId();
        try {
            $this->updater->execute($order, $checkoutForm);
            $this->logger->info("Order with id [$checkoutFormId] has been successfully updated");
        } catch (\Exception $e) {
            $this->logger->exception(
                $e,
                "Error while updating order with id [{$checkoutFormId}]"
            );
            throw $e;
        }
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     */
    private function removeErrorLogIfExist(CheckoutFormInterface $checkoutForm): void
    {
        $checkoutFormId = $checkoutForm->getId();
        try {
            $this->orderLogRepository->deleteByCheckoutFormId($checkoutFormId);
        } catch (CouldNotDeleteException $couldNotDeleteException) {
            $this->logger->exception(
                $couldNotDeleteException,
                "Error while deleting order with id [{$checkoutFormId}] from allegro_orders_with_errors table"
            );
        }
    }
}
