<?php


namespace Macopedia\Allegro\Model\Data\CheckoutForm\Delivery;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;

class Address extends DataObject implements AddressInterface
{
    const FIRST_NAME_FIELD_NAME = 'first_name';
    const LAST_NAME_FIELD_NAME = 'last_name';
    const PHONE_NUMBER_FIELD_NAME = 'phone_number';
    const ZIP_CODE_FIELD_NAME = 'zip_code';
    const CITY_FIELD_NAME = 'city';
    const STREET_FIELD_NAME = 'street';
    const COUNTRY_CODE_FIELD_NAME = 'country_code';
    const COMPANY_NAME_FIELD_NAME = 'company_name';

    /**
     * {@inheritDoc}
     */
    public function setFirstName(string $firstName)
    {
        $this->setData(self::FIRST_NAME_FIELD_NAME, $firstName);
    }

    /**
     * {@inheritDoc}
     */
    public function setLastName(string $lastName)
    {
        $this->setData(self::LAST_NAME_FIELD_NAME, $lastName);
    }

    /**
     * {@inheritDoc}
     */
    public function setPhoneNumber(string $phoneNumber)
    {
        $this->setData(self::PHONE_NUMBER_FIELD_NAME, $phoneNumber);
    }

    /**
     * {@inheritDoc}
     */
    public function setZipCode(string $zipCode)
    {
        $this->setData(self::ZIP_CODE_FIELD_NAME, $zipCode);
    }

    /**
     * {@inheritDoc}
     */
    public function setCity(string $city)
    {
        $this->setData(self::CITY_FIELD_NAME, $city);
    }

    /**
     * {@inheritDoc}
     */
    public function setStreet(string $city)
    {
        $this->setData(self::STREET_FIELD_NAME, $city);
    }

    /**
     * {@inheritDoc}
     */
    public function setCountryCode(string $countryCode)
    {
        $this->setData(self::COUNTRY_CODE_FIELD_NAME, $countryCode);
    }

    /**
     * {@inheritDoc}
     */
    public function setCompanyName(string $companyName)
    {
        $this->setData(self::COMPANY_NAME_FIELD_NAME, $companyName);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName(): ?string
    {
        return $this->getData(self::FIRST_NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPhoneNumber(): ?string
    {
        return $this->getData(self::PHONE_NUMBER_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getZipCode(): ?string
    {
        return $this->getData(self::ZIP_CODE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getStreet(): ?string
    {
        return $this->getData(self::STREET_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountryCode(): ?string
    {
        return $this->getData(self::COUNTRY_CODE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getCompanyName(): ?string
    {
        return $this->getData(self::COMPANY_NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function fillAddress(OrderAddressInterface|QuoteAddressInterface $address)
    {
        $address
            ->setFirstname($this->getFirstName() ?? 'unknown')
            ->setLastname($this->getLastName() ?? 'unknown')
            ->setTelephone($this->getPhoneNumber() ?? '123456789')
            ->setPostcode($this->getZipCode() ?? '12345')
            ->setCity($this->getCity() ?? 'unknown')
            ->setStreet($this->getStreet() ?? 'unknown')
            ->setCountryId($this->getCountryCode() ?? 'PL')
            ->setCompany($this->getCompanyName() ?? '');
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['firstName'])) {
            $this->setFirstName($rawData['firstName']);
        }
        if (isset($rawData['lastName'])) {
            $this->setLastName($rawData['lastName']);
        }
        if (isset($rawData['phoneNumber'])) {
            $this->setPhoneNumber($rawData['phoneNumber']);
        }
        if (isset($rawData['zipCode'])) {
            $this->setZipCode($rawData['zipCode']);
        }
        if (isset($rawData['city'])) {
            $this->setCity($rawData['city']);
        }
        if (isset($rawData['street'])) {
            $this->setStreet($rawData['street']);
        }
        if (isset($rawData['countryCode'])) {
            $this->setCountryCode($rawData['countryCode']);
        }
        if (isset($rawData['companyName'])) {
            $this->setCompanyName($rawData['companyName']);
        }
    }
}
