<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

class ShippingRates extends AbstractResource
{

    /**
     * @return mixed
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getList()
    {
        $response = $this->requestGet('/sale/shipping-rates?seller.id=' . $this->getCurrentUserId());
        return $response['shippingRates'] ?? [];
    }
}
