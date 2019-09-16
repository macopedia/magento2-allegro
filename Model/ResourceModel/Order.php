<?php

namespace Macopedia\Allegro\Model\ResourceModel;

use Magento\Sales\Model\ResourceModel\Order as ResourceModel;

class Order extends ResourceModel
{

    /**
     * @param string $checkoutFormId
     * @return int
     */
    public function getIdByAllegroCheckoutFormId(string $checkoutFormId): int
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from(['e' => 'sales_order'], 'e.entity_id')
            ->where('e.external_id = :externalId');

        $bind = [
            ':externalId' => (string)$checkoutFormId,
        ];

        return $connection->fetchOne($select, $bind);
    }
}
