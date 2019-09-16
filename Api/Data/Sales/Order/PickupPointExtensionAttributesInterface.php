<?php

namespace Macopedia\Allegro\Api\Data\Sales\Order;

interface PickupPointExtensionAttributesInterface
{

    public function setPointId(?string $pointId);
    public function setName(?string $name);
    public function setDescription(?string $description);
    public function setStreet(?string $street);
    public function setZipCode(?string $zipCode);
    public function setCity(?string $city);
    public function getPointId(): ?string;
    public function getName(): ?string;
    public function getDescription(): ?string;
    public function getStreet(): ?string;
    public function getZipCode(): ?string;
    public function getCity(): ?string;

}