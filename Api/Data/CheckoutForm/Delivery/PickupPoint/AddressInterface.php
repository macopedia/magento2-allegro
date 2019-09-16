<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPoint;

interface AddressInterface
{

    /**
     * @param string $street
     * @return void
     */
    public function setStreet(string $street);

    /**
     * @param string $zipCode
     * @return void
     */
    public function setZipCode(string $zipCode);

    /**
     * @param string $city
     * @return void
     */
    public function setCity(string $city);

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
