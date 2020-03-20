<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ReservationInterface;
use Macopedia\Allegro\Model\Reservation;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface ReservationRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ReservationInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * @param ReservationInterface $reservation
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(ReservationInterface $reservation): void;

    /**
     * @param ReservationInterface $reservation
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(ReservationInterface $reservation): void;

    /**
     * @param string $checkoutFormId
     * @return ReservationInterface[]
     * @throws NoSuchEntityException
     */
    public function getByCheckoutFormId(string $checkoutFormId): array;

    /**
     * @param int $reservationId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function deleteByReservationId(int $reservationId): void;

    /**
     * @param string $sku
     * @param string $checkoutFormId
     * @throws CouldNotDeleteException
     */
    public function deleteBySkuAndCheckoutFormId(string $sku, string $checkoutFormId): void;
}
