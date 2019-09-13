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
     * @param string $id
     * @return void
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->setData(self::NAME_FIELD_NAME, $name);
    }

    /**
     * @param string $paymentPolicy
     * @return void
     */
    public function setPaymentPolicy(string $paymentPolicy)
    {
        $this->setData(self::PAYMENT_POLICY_FIELD_NAME, $paymentPolicy);
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getPaymentPolicy(): ?string
    {
        return $this->getData(self::PAYMENT_POLICY_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @return void
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
