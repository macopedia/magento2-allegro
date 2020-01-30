<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model\ResourceModel;

use Macopedia\Allegro\Model\OrderLog as Model;
use \Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class OrderLog extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('allegro_orders_with_errors', 'entity_id');
    }

    /**
     * @param string $checkoutFormId
     */
    public function deleteByCheckoutFormId(string $checkoutFormId)
    {
        $this->getConnection()->delete(
            'allegro_orders_with_errors',
            [Model::CHECKOUT_FORM_ID_FIELD . ' = ?' => $checkoutFormId]
        );
    }
}
