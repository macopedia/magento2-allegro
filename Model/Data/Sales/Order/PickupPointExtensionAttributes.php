<?php

namespace Macopedia\Allegro\Model\Data\Sales\Order;

use Macopedia\Allegro\Api\Data\Sales\Order\PickupPointExtensionAttributesInterface;
use Magento\Framework\DataObject;

class PickupPointExtensionAttributes extends DataObject implements PickupPointExtensionAttributesInterface
{

    const POINT_ID_FIELD_NAME = 'point_id';
    const NAME_FIELD_NAME = 'name';
    const DESCRIPTION_FIELD_NAME = 'description';
    const STREET_FIELD_NAME = 'street';
    const ZIP_CODE_FIELD_NAME = 'zip_code';
    const CITY_FIELD_NAME = 'city';

    public function setPointId(?string $pointId)
    {
        $this->setData(self::POINT_ID_FIELD_NAME, $pointId);
    }

    public function setName(?string $name)
    {
        $this->setData(self::NAME_FIELD_NAME, $name);
    }

    public function setDescription(?string $description)
    {
        $this->setData(self::DESCRIPTION_FIELD_NAME, $description);
    }

    public function setStreet(?string $street)
    {
        $this->setData(self::STREET_FIELD_NAME, $street);
    }

    public function setZipCode(?string $zipCode)
    {
        $this->setData(self::ZIP_CODE_FIELD_NAME, $zipCode);
    }

    public function setCity(?string $city)
    {
        $this->setData(self::CITY_FIELD_NAME, $city);
    }

    public function getPointId(): ?string
    {
        return $this->getData(self::POINT_ID_FIELD_NAME);
    }

    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }

    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION_FIELD_NAME);
    }

    public function getStreet(): ?string
    {
        return $this->getData(self::STREET_FIELD_NAME);
    }

    public function getZipCode(): ?string
    {
        return $this->getData(self::ZIP_CODE_FIELD_NAME);
    }

    public function getCity(): ?string
    {
        return $this->getData(self::CITY_FIELD_NAME);
    }
}
