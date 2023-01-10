<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ReturnPolicyInterface;
use Magento\Framework\DataObject;

class ReturnPolicy extends DataObject implements ReturnPolicyInterface
{
    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';

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
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
    }
}
