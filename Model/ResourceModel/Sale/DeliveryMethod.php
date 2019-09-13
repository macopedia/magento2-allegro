<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get offers from Allegro API
 */
class DeliveryMethod extends AbstractResource
{

    /**
     * @return mixed
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getList()
    {
        $response = $this->requestGet('/sale/delivery-methods');
        return $response['deliveryMethods'];
    }
}
