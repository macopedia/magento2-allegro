<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\ResourceModel;

use Macopedia\Allegro\Model\Reservation as Model;
use \Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class Reservation extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('allegro_reservations', 'entity_id');
    }

    /**
     * @param int $reservationId
     */
    public function deleteByReservationId(int $reservationId)
    {
        $this->getConnection()->delete(
            'allegro_reservations',
            [Model::RESERVATION_ID_FIELD . ' = ?' => $reservationId]
        );
    }

    public function deleteBySkuAndCheckoutFormId(string $sku, string $checkoutFormId)
    {
        $this->getConnection()->delete(
            'allegro_reservations',
            [
                Model::SKU_FIELD . ' = ?' => $sku,
                Model::CHECKOUT_FORM_ID_FIELD . ' = ?' => $checkoutFormId
            ]
        );
    }
}
