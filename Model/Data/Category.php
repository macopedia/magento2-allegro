<?php


namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\CategoryInterface;
use Magento\Framework\DataObject;

class Category extends DataObject implements CategoryInterface
{

    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const LEAF_FIELD_NAME = 'leaf';
    const PARENT_FIELD_NAME = 'parent';

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
     * @param bool $leaf
     */
    public function setLeaf(bool $leaf)
    {
        $this->setData(self::LEAF_FIELD_NAME, $leaf);
    }

    /**
     * @param string $parent
     */
    public function setParent(string $parent)
    {
        $this->setData(self::PARENT_FIELD_NAME, $parent);
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
     * @return bool
     */
    public function getLeaf(): bool
    {
        return $this->getData(self::LEAF_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getParent(): ?string
    {
        return $this->getData(self::PARENT_FIELD_NAME);
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
        if (isset($rawData['parent']['id'])) {
            $this->setParent($rawData['parent']['id']);
        }
        $this->setLeaf($rawData['leaf'] ?? false);
    }
}
