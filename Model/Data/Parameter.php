<?php


namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ParameterInterface;
use Magento\Framework\DataObject;

abstract class Parameter extends DataObject implements ParameterInterface
{

    const ID_FIELD_NAME = 'id';

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID_FIELD_NAME);
    }
}
