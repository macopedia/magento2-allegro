<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;

interface AddressInterface
{

    public function setFirstName(string $firstName);
    public function setLastName(string $lastName);
    public function setPhoneNumber(string $phoneNumber);
    public function setZipCode(string $zipCode);
    public function setCity(string $city);
    public function setStreet(string $city);
    public function setCountryCode(string $countryCode);
    public function setCompanyName(string $companyName);

    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getPhoneNumber(): ?string;
    public function getZipCode(): ?string;
    public function getCity(): ?string;
    public function getStreet(): ?string;
    public function getCountryCode(): ?string;
    public function getCompanyName(): ?string;

    public function setRawData(array $rawData);

    /**
     * @param OrderAddressInterface|QuoteAddressInterface $orderAddress
     * @return void
     */
    public function fillAddress($orderAddress);
}
