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
    public function setLeaf(bool $leaf)
    {
        $this->setData(self::LEAF_FIELD_NAME, $leaf);
    }

    /**
     * {@inheritDoc}
     */
    public function setParent(string $parent)
    {
        $this->setData(self::PARENT_FIELD_NAME, $parent);
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
    public function getLeaf(): bool
    {
        return $this->getData(self::LEAF_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): ?string
    {
        return $this->getData(self::PARENT_FIELD_NAME);
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
        if (isset($rawData['parent']['id'])) {
            $this->setParent($rawData['parent']['id']);
        }
        $this->setLeaf($rawData['leaf'] ?? false);
    }
}
