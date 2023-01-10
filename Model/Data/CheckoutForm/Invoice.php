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
     * @param array $data
     */
    public function __construct(
        AddressInterfaceFactory $addressFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->addressFactory = $addressFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setAddress(AddressInterface $address)
    {
        $this->setData(self::ADDRESS_FIELD_NAME, $address);
    }

    /**
     * {@inheritDoc}
     */
    public function getAddress(): AddressInterface
    {
        return $this->getData(self::ADDRESS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        $this->setAddress($this->mapAddressData($rawData['address'] ?? []));
    }

    /**
     * @param array $data
     * @return AddressInterface
     */
    private function mapAddressData(array $data): AddressInterface
    {
        /** @var AddressInterface $address */
        $address = $this->addressFactory->create();
        $address->setRawData($data);
        return $address;
    }
}
