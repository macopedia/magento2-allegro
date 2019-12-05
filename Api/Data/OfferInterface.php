<?php

namespace Macopedia\Allegro\Api\Data;

use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterface;
use Macopedia\Allegro\Api\Data\Offer\LocationInterface;

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
     * @param string $ean
     * @return void
     */
    public function setEan(string $ean);

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
     * @param LocationInterface $location
     * @return void
     */
    public function setLocation(LocationInterface $location);

    /**
     * @param string $deliveryShippingRate
     * @return void
     */
    public function setDeliveryShippingRatesId(string $deliveryShippingRate);

    /**
     * @param string $handlingTime
     * @return void
     */
    public function setDeliveryHandlingTime(string $handlingTime);

    /**
     * @param string $paymentsInvoice
     * @return void
     */
    public function setPaymentsInvoice(string $paymentsInvoice);

    /**
     * @param string $publicationStatus
     * @return void
     */
    public function setPublicationStatus(string $publicationStatus);

    /**
     * @param string[] $validationErrors
     * @return void
     */
    public function setValidationErrors(array $validationErrors);

    /**
     * @param AfterSalesServicesInterface $afterSalesServices
     * @return mixed
     */
    public function setAfterSalesServices(AfterSalesServicesInterface $afterSalesServices);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getEan(): ?string;

    /**
     * @return LocationInterface
     */
    public function getLocation(): LocationInterface;

    /**
     * @return string|null
     */
    public function getDeliveryShippingRatesId(): ?string;

    /**
     * @return string|null
     */
    public function getDeliveryHandlingTime(): ?string;

    /**
     * @return string|null
     */
    public function getPaymentsInvoice(): ?string;

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
    public function getCategory(): ?string;

    /**
     * @return \Macopedia\Allegro\Api\Data\ParameterInterface[]
     */
    public function getParameters(): ?array;

    /**
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * @return int|null
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
     * @return string[]
     */
    public function getValidationErrors(): array;

    /**
     * @return AfterSalesServicesInterface
     */
    public function getAfterSalesServices(): AfterSalesServicesInterface;

    /**
     * @return bool
     */
    public function canBePublished(): bool;

    /**
     * @return bool
     */
    public function canBeEnded(): bool;

    /**
     * @return bool
     */
    public function isValid(): bool;

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
