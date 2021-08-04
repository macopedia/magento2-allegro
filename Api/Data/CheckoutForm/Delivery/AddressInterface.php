<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;

interface AddressInterface
{
    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName);

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName);

    /**
     * @param string $phoneNumber
     * @return void
     */
    public function setPhoneNumber(string $phoneNumber);

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
     * @param string $city
     * @return void
     */
    public function setStreet(string $city);

    /**
     * @param string $countryCode
     * @return void
     */
    public function setCountryCode(string $countryCode);

    /**
     * @param string $companyName
     * @return void
     */
    public function setCompanyName(string $companyName);

    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string;

    /**
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @param OrderAddressInterface|QuoteAddressInterface $address
     * @return void
     */
    public function fillAddress(OrderAddressInterface|QuoteAddressInterface $address);
}
