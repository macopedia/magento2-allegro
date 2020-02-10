<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ImpliedWarrantyInterface;
use Magento\Framework\DataObject;

class ImpliedWarranty extends DataObject implements ImpliedWarrantyInterface
{
    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';

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
    }
}
