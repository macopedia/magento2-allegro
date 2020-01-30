<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPoint\AddressInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface PickupPointInterface
{

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name);

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description);

    /**
     * @param AddressInterface $address
     * @return void
     */
    public function setAddress(AddressInterface $address);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface;

    /**
     * @param OrderInterface $order
     * @return void
     */
    public function fillOrder(OrderInterface $order);

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
