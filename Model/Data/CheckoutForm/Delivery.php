<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\MethodInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\MethodInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\CostInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\CostInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\AddressInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\DeliveryInterface;
use Magento\Framework\DataObject;

class Delivery extends DataObject implements DeliveryInterface
{

    const METHOD_FIELD_NAME = 'method';
    const ADDRESS_FIELD_NAME = 'address';
    const COST_FIELD_NAME = 'cost';

    /** @var MethodInterfaceFactory */
    private $methodFactory;

    /** @var AddressInterfaceFactory */
    private $addressFactory;

    /** @var CostInterfaceFactory */
    private $costFactory;

    /**
     * Delivery constructor.
     * @param MethodInterfaceFactory $methodFactory
     * @param AddressInterfaceFactory $addressFactory
     * @param CostInterface $costFactory
     */
    public function __construct(
        MethodInterfaceFactory $methodFactory,
        AddressInterfaceFactory $addressFactory,
        CostInterfaceFactory $costFactory
    ) {
        $this->methodFactory = $methodFactory;
        $this->addressFactory = $addressFactory;
        $this->costFactory = $costFactory;
    }

    /**
     * @param MethodInterface $method
     * @return void
     */
    public function setMethod(MethodInterface $method)
    {
        $this->setData(self::METHOD_FIELD_NAME, $method);
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
     * @param CostInterface $cost
     */
    public function setCost(CostInterface $cost)
    {
        $this->setData(self::COST_FIELD_NAME, $cost);
    }

    /**
     * @return MethodInterface
     */
    public function getMethod(): MethodInterface
    {
        return $this->getData(self::METHOD_FIELD_NAME);
    }

    /**
     * @return AddressInterface
     */
    public function getAddress(): AddressInterface
    {
        return $this->getData(self::ADDRESS_FIELD_NAME);
    }

    /**
     * @return CostInterface
     */
    public function getCost(): CostInterface
    {
        return $this->getData(self::COST_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData)
    {
        $this->setMethod($this->mapMethodData($rawData['method'] ?? []));
        $this->setAddress($this->mapAddressData($rawData['address'] ?? []));
        $this->setCost($this->mapCostData($rawData['cost'] ?? []));
    }

    /**
     * @param array $data
     * @return MethodInterface
     */
    private function mapMethodData(array $data): MethodInterface
    {
        /** @var MethodInterface $method */
        $method = $this->methodFactory->create();
        $method->setRawData($data);
        return $method;
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

    /**
     * @param array $data
     * @return CostInterface
     */
    private function mapCostData(array $data): CostInterface
    {
        /** @var CostInterface $cost */
        $cost = $this->costFactory->create();
        $cost->setRawData($data);
        return $cost;
    }
}
