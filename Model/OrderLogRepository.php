<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Macopedia\Allegro\Api\OrderLogRepositoryInterface;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\CollectionFactory;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\Collection;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Macopedia\Allegro\Model\ResourceModel\OrderLog;
use Magento\Framework\Exception\NoSuchEntityException;

class OrderLogRepository implements OrderLogRepositoryInterface
{
    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var OrderLogInterfaceFactory */
    private $orderLogFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var OrderLog */
    private $resource;

    /**
     * OrderLogRepository constructor.
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param OrderLog $resource
     * @param OrderLogInterfaceFactory $orderLogFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        OrderLog $resource,
        OrderLogInterfaceFactory $orderLogFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resource = $resource;
        $this->orderLogFactory = $orderLogFactory;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return array
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
     * @return int
     */
    public function getCount(): int
    {
        $collection = $this->collectionFactory->create();
        return (int)$this->resource->getConnection()->fetchOne($collection->getSelectCountSql());
    }

    /**
     * @param OrderLogInterface $orderLog
     * @throws CouldNotSaveException
     */
    public function save(OrderLogInterface $orderLog): void
    {
        try {
            $this->resource->save($orderLog);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save order log with id %1', $orderLog->getCheckoutFormId()), $e);
        }
    }

    /**
     * @param OrderLogInterface $orderLog
     * @throws CouldNotDeleteException
     */
    public function delete(OrderLogInterface $orderLog): void
    {
        try {
            $this->resource->delete($orderLog);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete order log with id %1', $orderLog->getCheckoutFormId()), $e);
        }
    }

    /**
     * @param string $checkoutFormId
     * @return OrderLogInterface
     * @throws NoSuchEntityException
     */
    public function getByCheckoutFormId(string $checkoutFormId): OrderLogInterface
    {
        /** @var OrderLogInterface $orderLog */
        $orderLog = $this->orderLogFactory->create();

        $this->resource->load($orderLog, $checkoutFormId, \Macopedia\Allegro\Model\OrderLog::CHECKOUT_FORM_ID_FIELD);
        if ($orderLog->getCheckoutFormId() === null) {
            throw new NoSuchEntityException(__('Could not load order log with id %1', $checkoutFormId));
        }
        return $orderLog;
    }

    /**
     * @param string $checkoutFormId
     * @throws CouldNotDeleteException
     */
    public function deleteByCheckoutFormId(string $checkoutFormId): void
    {
        try {
            $this->resource->deleteByCheckoutFormId($checkoutFormId);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete order log with id %1', $checkoutFormId), $e);
        }
    }
}
