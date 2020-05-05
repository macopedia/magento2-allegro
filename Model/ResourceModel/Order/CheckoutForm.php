<?php

namespace Macopedia\Allegro\Model\ResourceModel\Order;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get checkout forms from Allegro API
 */
class CheckoutForm extends AbstractResource
{
    /**
     * @param $checkoutFormId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getCheckoutForm($checkoutFormId)
    {
        return $this->requestGet('order/checkout-forms/' . $checkoutFormId, []);
    }

    /**
     * @param $checkoutFormId
     * @param array $shippingData
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function shipment($checkoutFormId, array $shippingData)
    {
        return $this->requestPost('order/checkout-forms/' . $checkoutFormId . '/shipments', $shippingData);
    }
}
