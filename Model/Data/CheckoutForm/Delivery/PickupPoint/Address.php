<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Delivery\PickupPoint;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPoint\AddressInterface;
use Magento\Framework\DataObject;

class Address extends DataObject implements AddressInterface
{

    const STREET_FIELD_NAME = 'street';
    const ZIP_CODE_FIELD_NAME = 'zipcode';
    const CITY_FIELD_NAME = 'city';

    /**
     * @param string $street
     */
    public function setStreet(string $street)
    {
        $this->setData(self::STREET_FIELD_NAME, $street);
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode)
    {
        $this->setData(self::ZIP_CODE_FIELD_NAME, $zipCode);
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->setData(self::CITY_FIELD_NAME, $city);
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->getData(self::STREET_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getZipCode(): ?string
    {
        return $this->getData(self::ZIP_CODE_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['street'])) {
            $this->setStreet($rawData['street']);
        }
        if (isset($rawData['zipCode'])) {
            $this->setZipCode($rawData['zipCode']);
        }
        if (isset($rawData['city'])) {
            $this->setCity($rawData['city']);
        }
    }
}
