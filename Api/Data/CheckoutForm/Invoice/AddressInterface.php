<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface as DeliveryAddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\NaturalPersonInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\CompanyInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;

interface AddressInterface
{

    public function setZipCode(string $zipCode);
    public function setCity(string $city);
    public function setStreet(string $city);
    public function setCountryCode(string $countryCode);
    public function setCompany(CompanyInterface $company);
    public function setNaturalPerson(NaturalPersonInterface $naturalPerson);

    public function getZipCode(): ?string;
    public function getCity(): ?string;
    public function getStreet(): ?string;
    public function getCountryCode(): ?string;
    public function getCompany(): CompanyInterface;
    public function getNaturalPerson(): NaturalPersonInterface;

    public function setRawData(array $rawData);

    /**
     * @param QuoteAddressInterface|OrderAddressInterface $address
     * @param DeliveryAddressInterface $deliveryAddress
     */
    public function fillAddress($address, DeliveryAddressInterface $deliveryAddress);
}
