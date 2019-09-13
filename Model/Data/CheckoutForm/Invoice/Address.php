<?php


namespace Macopedia\Allegro\Model\Data\CheckoutForm\Invoice;

use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\AddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\NaturalPersonInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\NaturalPersonInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\CompanyInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\CompanyInterfaceFactory;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\AddressInterface as QuoteAddressInterface;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface as DeliveryAddressInterface;

class Address extends DataObject implements AddressInterface
{

    const ZIP_CODE_FIELD_NAME = 'zip_code';
    const CITY_FIELD_NAME = 'city';
    const STREET_FIELD_NAME = 'street';
    const COUNTRY_CODE_FIELD_NAME = 'country_code';
    const COMPANY_FIELD_NAME = 'company';
    const NATURAL_PERSON_FIELD_NAME = 'natural_person';

    /** @var CompanyInterfaceFactory */
    private $companyFactory;

    /** @var NaturalPersonInterfaceFactory */
    private $naturalPersonFactory;

    /**
     * Address constructor.
     * @param CompanyInterfaceFactory $companyFactory
     */
    public function __construct(
        CompanyInterfaceFactory $companyFactory,
        NaturalPersonInterfaceFactory $naturalPersonFactory
    ) {
        $this->companyFactory = $companyFactory;
        $this->naturalPersonFactory = $naturalPersonFactory;
    }

    public function setZipCode(string $zipCode)
    {
        $this->setData(self::ZIP_CODE_FIELD_NAME, $zipCode);
    }

    public function setCity(string $city)
    {
        $this->setData(self::CITY_FIELD_NAME, $city);
    }

    public function setStreet(string $city)
    {
        $this->setData(self::STREET_FIELD_NAME, $city);
    }

    public function setCountryCode(string $countryCode)
    {
        $this->setData(self::COUNTRY_CODE_FIELD_NAME, $countryCode);
    }

    public function setCompany(CompanyInterface $company)
    {
        $this->setData(self::COMPANY_FIELD_NAME, $company);
    }

    public function setNaturalPerson(NaturalPersonInterface $naturalPerson)
    {
        $this->setData(self::NATURAL_PERSON_FIELD_NAME, $naturalPerson);
    }

    public function getZipCode(): ?string
    {
        return $this->getData(self::ZIP_CODE_FIELD_NAME);
    }

    public function getCity(): ?string
    {
        return $this->getData(self::CITY_FIELD_NAME);
    }

    public function getStreet(): ?string
    {
        return $this->getData(self::STREET_FIELD_NAME);
    }

    public function getCountryCode(): ?string
    {
        return $this->getData(self::COUNTRY_CODE_FIELD_NAME);
    }

    public function getCompany(): CompanyInterface
    {
        return $this->getData(self::COMPANY_FIELD_NAME);
    }

    public function getNaturalPerson(): NaturalPersonInterface
    {
        return $this->getData(self::NATURAL_PERSON_FIELD_NAME);
    }

    /**
     * @param QuoteAddressInterface|OrderAddressInterface $address
     * @param DeliveryAddressInterface $deliveryAddress
     */
    public function fillAddress($address, DeliveryAddressInterface $deliveryAddress)
    {
        $address->setFirstname(
            $this->getNaturalPerson()->getFirstName() ?? $deliveryAddress->getFirstName() ?? 'unknown'
        );
        $address->setLastname(
            $this->getNaturalPerson()->getLastName() ?? $deliveryAddress->getLastName() ?? 'unknown'
        );
        $address->setCompany(
            $this->getCompany()->getName() ?? $deliveryAddress->getCompanyName()
        );
        $address->setPostcode(
            $this->getZipCode() ?? $deliveryAddress->getZipCode() ?? '12345'
        );
        $address->setCity(
            $this->getCity() ?? $deliveryAddress->getCity() ?? 'unknown'
        );
        $address->setStreet(
            $this->getStreet() ?? $deliveryAddress->getStreet() ?? 'unknown'
        );
        $address->setCountryId(
            $this->getCountryCode() ?? $deliveryAddress->getCountryCode() ?? 'PL'
        );
        $address->setTelephone(
            $deliveryAddress->getPhoneNumber() ?? '123456789'
        );
    }

    public function setRawData(array $rawData)
    {
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

        $this->setCompany($this->mapCompanyData($rawData['company'] ?? []));
        $this->setNaturalPerson($this->mapNaturalPersonData($rawData['naturalPerson'] ?? []));
    }

    /**
     * @param array $data
     * @return CompanyInterface
     */
    private function mapCompanyData(array $data): CompanyInterface
    {
        /** @var CompanyInterface $company */
        $company = $this->companyFactory->create();
        $company->setRawData($data);
        return $company;
    }

    /**
     * @param array $data
     * @return NaturalPersonInterface
     */
    private function mapNaturalPersonData(array $data): NaturalPersonInterface
    {
        /** @var NaturalPersonInterface $naturalPerson */
        $naturalPerson = $this->naturalPersonFactory->create();
        $naturalPerson->setRawData($data);
        return $naturalPerson;
    }
}
