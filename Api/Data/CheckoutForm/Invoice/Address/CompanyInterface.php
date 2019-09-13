<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address;

interface CompanyInterface
{

    public function setName(string $name);
    public function setVatId(string $vatId);

    public function getName(): ?string;
    public function getVatId(): ?string;

    public function setRawData(array $rawData);
}
