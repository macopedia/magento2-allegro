<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ReservationInterface;
use Magento\Framework\Model\AbstractModel;
use Macopedia\Allegro\Model\ResourceModel\Reservation as ResourceModel;
use Magento\InventoryReservationsApi\Model\ReservationInterface as OriginalReservationInterface;

class Reservation extends AbstractModel implements ReservationInterface
{
    /**#@+
     * Constants for columns names
     */
    const ENTITY_ID_FIELD = 'entity_id';
    const RESERVATION_ID_FIELD = 'reservation_id';
    const CHECKOUT_FORM_ID_FIELD = 'checkout_form_id';
    const SKU_FIELD = 'sku';
    /**#@-*/

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(self::ENTITY_ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getReservationId(): int
    {
        return (int)$this->getData(self::RESERVATION_ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getCheckoutFormId(): string
    {
        return $this->getData(self::CHECKOUT_FORM_ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getSku(): string
    {
        return $this->getData(self::SKU_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function setReservationId(int $reservationId): void
    {
        $this->setData(self::RESERVATION_ID_FIELD, $reservationId);
    }

    /**
     * @inheritDoc
     */
    public function setCheckoutFormId(string $checkoutFormId): void
    {
        $this->setData(self::CHECKOUT_FORM_ID_FIELD, $checkoutFormId);
    }

    /**
     * @inheritDoc
     */
    public function setSku(string $sku): void
    {
        $this->setData(self::SKU_FIELD, $sku);
    }
}
