<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface OrderLogRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return OrderLogInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria) : array;

    /**
     * @return int
     */
    public function getCount() : int;

    /**
     * @param OrderLogInterface $orderLog
     * @throws CouldNotSaveException
     * @return void
     */
    public function save(OrderLogInterface $orderLog) : void;

    /**
     * @param OrderLogInterface $orderLog
     * @throws CouldNotDeleteException
     * @return void
     */
    public function delete(OrderLogInterface $orderLog) : void;

    /**
     * @param string $checkoutFormId
     * @throws NoSuchEntityException
     * @return OrderLogInterface
     */
    public function getByCheckoutFormId(string $checkoutFormId) : OrderLogInterface;

    /**
     * @param string $checkoutFormId
     * @throws CouldNotDeleteException
     * @return void
     */
    public function deleteByCheckoutFormId(string $checkoutFormId) : void;
}
