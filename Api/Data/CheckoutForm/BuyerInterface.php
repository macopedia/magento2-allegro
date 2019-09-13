<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface BuyerInterface
{

    public function setFirstName(string $firstName);
    public function setLastName(string $lastName);
    public function setEmail(string $email);

    public function getFirstName(): ?string;
    public function getLastName(): ?string;
    public function getEmail(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
