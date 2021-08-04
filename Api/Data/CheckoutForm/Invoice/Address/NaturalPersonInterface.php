<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address;

interface NaturalPersonInterface
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
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
