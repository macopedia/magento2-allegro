<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\CostInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\MethodInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPointInterface;

interface DeliveryInterface
{
    /**
     * @param MethodInterface $method
     * @return void
     */
    public function setMethod(MethodInterface $method);

    /**
     * @param AddressInterface $address
     * @return void
     */
    public function setAddress(AddressInterface $address);

    /**
     * @param CostInterface $cost
     * @return void
     */
    public function setCost(CostInterface $cost);

    /**
     * @param PickupPointInterface $pickupPoint
     * @return void
     */
    public function setPickupPoint(PickupPointInterface $pickupPoint);

    /**
     * @return MethodInterface
     */
    public function getMethod(): MethodInterface;

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface;

    /**
     * @return CostInterface
     */
    public function getCost(): CostInterface;

    /**
     * @return PickupPointInterface
     */
    public function getPickupPoint(): PickupPointInterface;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
