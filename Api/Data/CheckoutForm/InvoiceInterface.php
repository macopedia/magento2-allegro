<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\AddressInterface;

interface InvoiceInterface
{
    /**
     * @param AddressInterface $address
     * @return void
     */
    public function setAddress(AddressInterface $address);

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
