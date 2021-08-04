<?php

namespace Macopedia\Allegro\Api\Data\Sales\Order;

interface PickupPointExtensionAttributesInterface
{
    /**
     * @param string|null $pointId
     * @return void
     */
    public function setPointId(?string $pointId);

    /**
     * @param string|null $name
     * @return void
     */
    public function setName(?string $name);

    /**
     * @param string|null $description
     * @return void
     */
    public function setDescription(?string $description);

    /**
     * @param string|null $street
     * @return void
     */
    public function setStreet(?string $street);

    /**
     * @param string|null $zipCode
     * @return void
     */
    public function setZipCode(?string $zipCode);

    /**
     * @param string|null $city
     * @return void
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
