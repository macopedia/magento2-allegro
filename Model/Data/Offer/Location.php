<?php

namespace Macopedia\Allegro\Model\Data\Offer;

use Macopedia\Allegro\Api\Data\Offer\LocationInterface;
use Magento\Framework\DataObject;

class Location extends DataObject implements LocationInterface
{

    const COUNTRY_CODE_FIELD_NAME = 'country_code';
    const PROVINCE_FIELD_NAME = 'province';
    const CITY_FIELD_NAME = 'city';
    const POST_CODE_FIELD_NAME = 'post_code';

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode)
    {
        $this->setData(self::COUNTRY_CODE_FIELD_NAME, $countryCode);
    }

    /**
     * @param string $province
     */
    public function setProvince(string $province)
    {
        $this->setData(self::PROVINCE_FIELD_NAME, $province);
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->setData(self::CITY_FIELD_NAME, $city);
    }

    /**
     * @param string $postCode
     */
    public function setPostCode(string $postCode)
    {
        $this->setData(self::POST_CODE_FIELD_NAME, $postCode);
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->getData(self::COUNTRY_CODE_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getProvince(): ?string
    {
        return $this->getData(self::PROVINCE_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->getData(self::CITY_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getPostCode(): ?string
    {
        return $this->getData(self::POST_CODE_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['countryCode'])) {
            $this->setCountryCode($rawData['countryCode']);
        }
        if (isset($rawData['province'])) {
            $this->setProvince($rawData['province']);
        }
        if (isset($rawData['city'])) {
            $this->setCity($rawData['city']);
        }
        if (isset($rawData['postCode'])) {
            $this->setPostCode($rawData['postCode']);
        }
    }

    /**
     * @return array
     */
    public function getRawData(): array
    {
        return [
            'countryCode' => $this->getCountryCode(),
            'province' => $this->getProvince(),
            'city' => $this->getCity(),
            'postCode' => $this->getPostCode(),
        ];
    }
}