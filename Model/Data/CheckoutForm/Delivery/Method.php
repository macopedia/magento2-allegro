<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Delivery;

use Macopedia\Allegro\Api\Data\CheckoutForm\Delivery\MethodInterface;
use Magento\Framework\DataObject;

class Method extends DataObject implements MethodInterface
{
    const ID_FIELD_NAME = 'id';

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
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
    }
}
