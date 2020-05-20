<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ReservationInterface;
use Macopedia\Allegro\Api\Data\ReservationInterfaceFactory;
use Macopedia\Allegro\Api\ReservationRepositoryInterface;
use Macopedia\Allegro\Model\ResourceModel\Reservation\CollectionFactory;
use Macopedia\Allegro\Model\ResourceModel\Reservation\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Macopedia\Allegro\Model\ResourceModel\Reservation as ResourceModel;

class ReservationRepository implements ReservationRepositoryInterface
{
    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var ResourceModel */
    private $resource;

    /** @var ReservationInterfaceFactory */
    private $reservationFactory;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * ReservationLogRepository constructor.
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ResourceModel $resource
     * @param ReservationInterfaceFactory $reservationFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ResourceModel $resource,
        ReservationInterfaceFactory $reservationFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resource = $resource;
        $this->reservationFactory = $reservationFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        return $collection->getItems();
    }

    /**
     * @inheritDoc
     */
    public function save(ReservationInterface $reservation): void
    {
        try {
            $this->resource->save($reservation);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__(
                'Could not save reservation for order with id %1',
                $reservation->getCheckoutFormId()
            ), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(ReservationInterface $reservation): void
    {
        try {
            $this->resource->delete($reservation);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__(
                'Could not delete reservation for order with id %1',
                $reservation->getCheckoutFormId()
            ), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getByCheckoutFormId(string $checkoutFormId): array
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(Reservation::CHECKOUT_FORM_ID_FIELD, $checkoutFormId)
            ->create();

        return $this->getList($searchCriteria);
    }

    /**
     * @inheritDoc
     */
    public function deleteByReservationId(int $reservationId): void
    {
        try {
            $this->resource->deleteByReservationId($reservationId);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete reservation with id %1', $reservationId), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deleteBySkuAndCheckoutFormId(string $sku, string $checkoutFormId): void
    {
        try {
            $this->resource->deleteBySkuAndCheckoutFormId($sku, $checkoutFormId);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(
                __('Could not delete reservation for order with id %1', $checkoutFormId),
                $e
            );
        }
    }
}
