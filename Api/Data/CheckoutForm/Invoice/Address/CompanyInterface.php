<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address;

interface CompanyInterface
{
    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name);

    /**
     * @param string $vatId
     * @return void
     */
    public function setVatId(string $vatId);

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getVatId(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
