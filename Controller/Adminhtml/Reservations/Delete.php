<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Controller\Adminhtml\Reservations;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Reservation;
use Macopedia\Allegro\Model\ResourceModel\Reservation\Collection;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Macopedia\Allegro\Model\ResourceModel\Reservation\CollectionFactory;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Delete controller class
 */
class Delete extends Action
{
    /** @var Logger */
    private $logger;

    /** @var Filter */
    private $filter;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var AllegroReservation */
    private $allegroReservation;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param Logger $logger
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param AllegroReservation $allegroReservation
     */
    public function __construct(
        Action\Context $context,
        Logger $logger,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AllegroReservation $allegroReservation
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->allegroReservation = $allegroReservation;
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result->setPath('*/*/');

        try {
            /** @var Collection $collection */
            $collection = $this->filter->getCollection($this->collectionFactory->create());
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e);
            return $result;
        }
        /** @var Reservation $item */
        foreach ($collection as $item) {
            $checkoutFormId = $item->getCheckoutFormId();
            $reservationId = $item->getReservationId();
            try {
                $this->allegroReservation->compensateReservation($checkoutFormId);
                $this->logger->info(__("Reservation with ID: %1 has been successfully deleted", $reservationId));
                $this->messageManager->addSuccessMessage(__(
                    "Reservation with ID: %1 has been successfully deleted",
                    $reservationId
                ));
            } catch (\Exception $e) {
                $this->logger->exception($e);
                $this->messageManager->addErrorMessage(__(
                    "Something went wrong while trying to delete reservation with ID: %1",
                    $reservationId
                ));
            }
        }
        return $result;
    }
}
