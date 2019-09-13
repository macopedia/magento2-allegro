<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\EventInterface;
use Magento\Framework\DataObject;

class Event extends DataObject implements EventInterface
{

    const ID_FIELD_NAME = 'id';
    const TYPE_FIELD_NAME = 'type';
    const CHECKOUT_FORM_ID_FIELD_NAME = 'checkout_form_id';

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }
    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * @param string $checkoutFormId
     * @return void
     */
    public function setCheckoutFormId(string $checkoutFormId)
    {
        $this->setData(self::CHECKOUT_FORM_ID_FIELD_NAME, $checkoutFormId);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getCheckoutFormId(): string
    {
        return $this->getData(self::CHECKOUT_FORM_ID_FIELD_NAME);
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
        if (isset($rawData['type'])) {
            $this->setType($rawData['type']);
        }
        if (isset($rawData['order']['checkoutForm']['id'])) {
            $this->setCheckoutFormId($rawData['order']['checkoutForm']['id']);
        }
    }
}
