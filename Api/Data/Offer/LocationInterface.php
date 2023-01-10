<?php

namespace Macopedia\Allegro\Api\Data\Offer;

interface LocationInterface
{
    /**
     * @param string $countryCode
     * @return void
     */
    public function setCountryCode(string $countryCode);

    /**
     * @param string $province
     * @return void
     */
    public function setProvince(string $province);

    /**
     * @param string $city
     * @return void
     */
    public function setCity(string $city);

    /**
     * @param string $postCode
     * @return void
     */
    public function setPostCode(string $postCode);

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @return string|null
     */
    public function getProvince(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @return string|null
     */
    public function getPostCode(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @return array
     */
    public function getRawData(): array;
}
