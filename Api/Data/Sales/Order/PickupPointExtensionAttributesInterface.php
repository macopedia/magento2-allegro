<?php

namespace Macopedia\Allegro\Api\Data\Sales\Order;

interface PickupPointExtensionAttributesInterface
{

    /**
     * @param string|null $pointId
     * @return mixed
     */
    public function setPointId(?string $pointId);

    /**
     * @param string|null $name
     * @return mixed
     */
    public function setName(?string $name);

    /**
     * @param string|null $description
     * @return mixed
     */
    public function setDescription(?string $description);

    /**
     * @param string|null $street
     * @return mixed
     */
    public function setStreet(?string $street);

    /**
     * @param string|null $zipCode
     * @return mixed
     */
    public function setZipCode(?string $zipCode);

    /**
     * @param string|null $city
     * @return mixed
     */
    public function setCity(?string $city);

    /**
     * @return string|null
     */
    public function getPointId(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @return string|null
     */
    public function getZipCode(): ?string;

    /**
     * @return string|null
     */
    public function getCity(): ?string;
}
