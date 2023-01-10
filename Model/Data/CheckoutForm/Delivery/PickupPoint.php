<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Delivery;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPoint\AddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPoint\AddressInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\PickupPointInterface;
use Macopedia\Allegro\Api\Data\Sales\Order\PickupPointExtensionAttributesInterface;
use Macopedia\Allegro\Api\Data\Sales\Order\PickupPointExtensionAttributesInterfaceFactory;
use Magento\Framework\DataObject;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderInterface;

class PickupPoint extends DataObject implements PickupPointInterface
{
    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const DESCRIPTION_FIELD_NAME = 'description';
    const ADDRESS_FIELD_NAME = 'address';

    /** @var AddressInterfaceFactory */
    private $addressFactory;

    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /** @var PickupPointExtensionAttributesInterfaceFactory */
    private $pickupPointExtensionAttributesFactory;

    /**
     * PickupPoint constructor.
     * @param AddressInterfaceFactory $addressFactory
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param PickupPointExtensionAttributesInterfaceFactory $pickupPointExtensionAttributesFactory
     * @param array $data
     */
    public function __construct(
        AddressInterfaceFactory $addressFactory,
        OrderExtensionFactory $orderExtensionFactory,
        PickupPointExtensionAttributesInterfaceFactory $pickupPointExtensionAttributesFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->addressFactory = $addressFactory;
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->pickupPointExtensionAttributesFactory = $pickupPointExtensionAttributesFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $name)
    {
        $this->setData(self::NAME_FIELD_NAME, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(string $description)
    {
        $this->setData(self::DESCRIPTION_FIELD_NAME, $description);
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
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION_FIELD_NAME);
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
    public function fillOrder(OrderInterface $order)
    {
        if ($this->getId() === null) {
            return;
        }

        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        /** @var PickupPointExtensionAttributesInterface $pickupPoint */
        $pickupPoint = $extensionAttributes->getPickupPoint();
        if ($pickupPoint === null) {
            $pickupPoint = $this->pickupPointExtensionAttributesFactory->create();
        }

        $pickupPoint->setPointId($this->getId());
        $pickupPoint->setName($this->getName());
        $pickupPoint->setDescription($this->getDescription());
        $pickupPoint->setStreet($this->getAddress()->getStreet());
        $pickupPoint->setZipCode($this->getAddress()->getZipCode());
        $pickupPoint->setCity($this->getAddress()->getCity());

        $extensionAttributes->setPickupPoint($pickupPoint);
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
        if (isset($rawData['description'])) {
            $this->setDescription($rawData['description']);
        }
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
