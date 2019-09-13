<?php

namespace Macopedia\Allegro\Api\Data;

interface OfferInterface
{

    const PUBLICATION_STATUS_ACTIVE = 'ACTIVE';
    const PUBLICATION_STATUS_ACTIVATING = 'ACTIVATING';
    const PUBLICATION_STATUS_INACTIVE = 'INACTIVE';
    const PUBLICATION_STATUS_ENDED = 'ENDED';

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name);

    /**
     * @param string $description
     * @return void
     */
    public function setDescription(string $description);

    /**
     * @param string $category
     * @return void
     */
    public function setCategory(string $category);

    /**
     * @param \Macopedia\Allegro\Api\Data\ParameterInterface[] $parameters
     * @return void
     */
    public function setParameters(array $parameters);

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price);

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty);

    /**
     * @param \Macopedia\Allegro\Api\Data\ImageInterface[] $images
     * @return void
     */
    public function setImages(array $images);

    /**
     * @param array $location
     * @return void
     */
    public function setLocation(array $location);

    /**
     * @param array $payments
     * @return void
     */
    public function setPayments(array $payments);

    /**
     * @param array $delivery
     * @return void
     */
    public function setDelivery(array $delivery);

    /**
     * @param string $publicationStatus
     * @return void
     */
    public function setPublicationStatus(string $publicationStatus);

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return array|null
     */
    public function getLocation(): ?array;

    /**
     * @return array|null
     */
    public function getPayments(): ?array;

    /**
     * @return array|null
     */
    public function getDelivery(): ?array;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * @return string
     */
    public function getCategory(): ?string;

    /**
     * @return \Macopedia\Allegro\Api\Data\ParameterInterface[]
     */
    public function getParameters(): ?array;

    /**
     * @return float
     */
    public function getPrice(): ?float;

    /**
     * @return int
     */
    public function getQty(): ?int;

    /**
     * @return \Macopedia\Allegro\Api\Data\ImageInterface[]
     */
    public function getImages(): ?array;

    /**
     * @return string|null
     */
    public function getPublicationStatus(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @return array
     */
    public function getRawData(): array;
}
