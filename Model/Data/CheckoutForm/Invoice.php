<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\AddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\AddressInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\MethodInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\InvoiceInterface;
use Magento\Framework\DataObject;

class Invoice extends DataObject implements InvoiceInterface
{

    const ADDRESS_FIELD_NAME = 'address';

    /** @var AddressInterfaceFactory */
    private $addressFactory;

    /**
     * Delivery constructor.
     * @param AddressInterfaceFactory $addressFactory
     */
    public function __construct(AddressInterfaceFactory $addressFactory)
    {
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param AddressInterface $address
     * @return void
     */
    public function setAddress(AddressInterface $address)
    {
        $this->setData(self::ADDRESS_FIELD_NAME, $address);
    }

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface
    {
        return $this->getData(self::ADDRESS_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData)
    {
        $this->setAddress($this->mapAddressData($rawData['address'] ?? []));
    }

    /**
     * @param array $data
     * @return MethodInterface
     */
    private function mapAddressData(array $data): AddressInterface
    {
        /** @var AddressInterface $address */
        $address = $this->addressFactory->create();
        $address->setRawData($data);
        return $address;
    }
}
