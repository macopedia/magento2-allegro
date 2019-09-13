<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address;

interface NaturalPersonInterface
{

    public function setFirstName(string $firstName);
    public function setLastName(string $lastName);

    public function getFirstName(): ?string;
    public function getLastName(): ?string;

    public function setRawData(array $rawData);
}
