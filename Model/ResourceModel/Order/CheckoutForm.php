<?php

namespace Macopedia\Allegro\Model\ResourceModel\Order;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseErrorException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
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
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
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
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     */
    public function shipment($checkoutFormId, array $shippingData)
    {
        return $this->requestPost('order/checkout-forms/' . $checkoutFormId . '/shipments', $shippingData);
    }

    /**
     * @param $checkoutFormId
     * @param string $status
     * @return array
     * @throws ClientException
     * @throws ClientResponseErrorException
     * @throws ClientResponseException
     */
    public function changeOrderStatus($checkoutFormId, string $status)
    {
        return $this->requestPut('order/checkout-forms/' . $checkoutFormId . '/fulfillment', ['status' => $status]);
    }
}
