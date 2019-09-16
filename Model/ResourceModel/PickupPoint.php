<?php

namespace Macopedia\Allegro\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PickupPoint extends AbstractDb
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('sales_order_pickup_point', 'entity_id');
    }
}
