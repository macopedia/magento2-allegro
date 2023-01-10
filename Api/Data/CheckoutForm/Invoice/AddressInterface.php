<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface as DeliveryAddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\NaturalPersonInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\CompanyInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;

interface AddressInterface
{
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
     * @param CompanyInterface $company
     * @return void
     */
    public function setCompany(CompanyInterface $company);

    /**
     * @param NaturalPersonInterface $naturalPerson
     * @return void
     */
    public function setNaturalPerson(NaturalPersonInterface $naturalPerson);

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
     * @return CompanyInterface
     */
    public function getCompany(): CompanyInterface;

    /**
     * @return NaturalPersonInterface
     */
    public function getNaturalPerson(): NaturalPersonInterface;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @param OrderAddressInterface|QuoteAddressInterface $address
     * @param DeliveryAddressInterface $deliveryAddress
     */
    public function fillAddress(OrderAddressInterface|QuoteAddressInterface $address, DeliveryAddressInterface $deliveryAddress);
}
