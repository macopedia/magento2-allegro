<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\DeliveryMethodInterface;
use Magento\Framework\DataObject;

class DeliveryMethod extends DataObject implements DeliveryMethodInterface
{
    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const PAYMENT_POLICY_FIELD_NAME = 'payment_policy';

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
    public function setPaymentPolicy(string $paymentPolicy)
    {
        $this->setData(self::PAYMENT_POLICY_FIELD_NAME, $paymentPolicy);
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
    public function getPaymentPolicy(): ?string
    {
        return $this->getData(self::PAYMENT_POLICY_FIELD_NAME);
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
        if (isset($rawData['paymentPolicy'])) {
            $this->setPaymentPolicy($rawData['paymentPolicy']);
        }
    }
}
