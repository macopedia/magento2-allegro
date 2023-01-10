<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\Data\ImageInterfaceFactory;
use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterface;
use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterfaceFactory;
use Macopedia\Allegro\Api\Data\Offer\LocationInterface;
use Macopedia\Allegro\Api\Data\Offer\LocationInterfaceFactory;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;
use Magento\Framework\DataObject;

class Offer extends DataObject implements OfferInterface
{
    const ID_FIELD_NAME = 'id';
    const EAN_FIELD_NAME = 'ean';
    const NAME_FIELD_NAME = 'name';
    const DESCRIPTION_FIELD_NAME = 'description';
    const QTY_FIELD_NAME = 'qty';
    const PRICE_FIELD_NAME = 'price';
    const CATEGORY_FIELD_NAME = 'category';
    const PARAMETERS_FIELD_NAME = 'parameters';
    const IMAGES_FIELD_NAME = 'images';
    const LOCATION_FIELD_NAME = 'location';
    const DELIVERY_SHIPPING_RATES_ID_FIELD_NAME = 'delivery_shipping_rates_id';
    const DELIVERY_HANDLING_TIME_FIELD_NAME = 'delivery_handling_time';
    const PAYMENTS_INVOICE_FIELD_NAME = 'payments_invoice';
    const PUBLICATION_STATUS_FIELD_NAME = 'publication_status';
    const VALIDATION_ERRORS_FIELD_NAME = 'validation_errors';
    const AFTER_SALES_SERVICES_FIELD_NAME = 'after_sales_services';

    /** @var ParameterDefinitionRepositoryInterface */
    private $parameterDefinitionRepository;

    /** @var ImageInterfaceFactory */
    private $imageFactory;

    /** @var LocationInterfaceFactory */
    private $locationFactory;

    /** @var AfterSalesServicesInterfaceFactory */
    private $afterSalesServicesFactory;

    /**
     * Offer constructor.
     * @param ParameterDefinitionRepositoryInterface $parameterDefinitionRepository
     * @param ImageInterfaceFactory $imageFactory
     * @param LocationInterfaceFactory $locationFactory
     * @param AfterSalesServicesInterfaceFactory $afterSalesServicesFactory
     */
    public function __construct(
        ParameterDefinitionRepositoryInterface $parameterDefinitionRepository,
        ImageInterfaceFactory $imageFactory,
        LocationInterfaceFactory $locationFactory,
        AfterSalesServicesInterfaceFactory $afterSalesServicesFactory
    ) {
        $this->parameterDefinitionRepository = $parameterDefinitionRepository;
        $this->imageFactory = $imageFactory;
        $this->locationFactory = $locationFactory;
        $this->afterSalesServicesFactory = $afterSalesServicesFactory;
        $this->setRawData([]);
    }

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
    public function setEan(string $ean)
    {
        $this->setData(self::EAN_FIELD_NAME, $ean);
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
    public function setDescription(string $description)
    {
        $this->setData(self::DESCRIPTION_FIELD_NAME, $description);
    }

    /**
     * {@inheritDoc}
     */
    public function setCategory(string $category)
    {
        $this->setData(self::CATEGORY_FIELD_NAME, $category);
    }

    /**
     * {@inheritDoc}
     */
    public function setQty(int $qty)
    {
        $this->setData(self::QTY_FIELD_NAME, $qty);
    }

    /**
     * {@inheritDoc}
     */
    public function setPrice(float $price)
    {
        $this->setData(self::PRICE_FIELD_NAME, $price);
    }

    /**
     * {@inheritDoc}
     */
    public function setParameters(array $parameters)
    {
        $this->setData(self::PARAMETERS_FIELD_NAME, $parameters);
    }

    /**
     * {@inheritDoc}
     */
    public function setImages(array $images)
    {
        $this->setData(self::IMAGES_FIELD_NAME, $images);
    }

    /**
     * {@inheritDoc}
     */
    public function setLocation(LocationInterface $location)
    {
        $this->setData(self::LOCATION_FIELD_NAME, $location);
    }

    /**
     * {@inheritDoc}
     */
    public function setDeliveryShippingRatesId(string $deliveryShippingRate)
    {
        $this->setData(self::DELIVERY_SHIPPING_RATES_ID_FIELD_NAME, $deliveryShippingRate);
    }

    /**
     * {@inheritDoc}
     */
    public function setDeliveryHandlingTime(string $handlingTime)
    {
        $this->setData(self::DELIVERY_HANDLING_TIME_FIELD_NAME, $handlingTime);
    }

    /**
     * {@inheritDoc}
     */
    public function setPaymentsInvoice(string $paymentsInvoice)
    {
        $this->setData(self::PAYMENTS_INVOICE_FIELD_NAME, $paymentsInvoice);
    }

    /**
     * {@inheritDoc}
     */
    public function setPublicationStatus(string $publicationStatus)
    {
        $this->setData(self::PUBLICATION_STATUS_FIELD_NAME, $publicationStatus);
    }

    /**
     * {@inheritDoc}
     */
    public function setValidationErrors(array $validationErrors)
    {
        $this->setData(self::VALIDATION_ERRORS_FIELD_NAME, $validationErrors);
    }

    /**
     * {@inheritDoc}
     */
    public function setAfterSalesServices(AfterSalesServicesInterface $afterSalesServices)
    {
        $this->setData(self::AFTER_SALES_SERVICES_FIELD_NAME, $afterSalesServices);
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
    public function getEan(): ?string
    {
        return $this->getData(self::EAN_FIELD_NAME);
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
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategory(): ?string
    {
        return $this->getData(self::CATEGORY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getQty(): ?int
    {
        return $this->getData(self::QTY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice(): ?float
    {
        return $this->getData(self::PRICE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getParameters(): array
    {
        return (array)$this->getData(self::PARAMETERS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getImages(): array
    {
        return (array)$this->getData(self::IMAGES_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLocation(): LocationInterface
    {
        return $this->getData(self::LOCATION_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getDeliveryShippingRatesId(): ?string
    {
        return $this->getData(self::DELIVERY_SHIPPING_RATES_ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getDeliveryHandlingTime(): ?string
    {
        return $this->getData(self::DELIVERY_HANDLING_TIME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentsInvoice(): ?string
    {
        return $this->getData(self::PAYMENTS_INVOICE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPublicationStatus(): ?string
    {
        return $this->getData(self::PUBLICATION_STATUS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getValidationErrors(): array
    {
        return $this->getData(self::VALIDATION_ERRORS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getAfterSalesServices(): AfterSalesServicesInterface
    {
        return $this->getData(self::AFTER_SALES_SERVICES_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function canBePublished(): bool
    {
        return in_array(
            $this->getPublicationStatus(),
            [
                self::PUBLICATION_STATUS_INACTIVE,
                self::PUBLICATION_STATUS_ENDED
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function canBeEnded(): bool
    {
        return $this->getPublicationStatus() == self::PUBLICATION_STATUS_ACTIVE;
    }

    /**
     * {@inheritDoc}
     */
    public function isDraft(): bool
    {
        return $this->getPublicationStatus() == self::PUBLICATION_STATUS_INACTIVE;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid(): bool
    {
        return count($this->getValidationErrors()) < 1;
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['ean'])) {
            $this->setEan($rawData['ean']);
        }
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
        if (isset($rawData['description']['sections'][0]['items'][0]['content'])) {
            $this->setDescription($rawData['description']['sections'][0]['items'][0]['content']);
        }
        if (isset($rawData['category']['id'])) {
            $this->setCategory($rawData['category']['id']);
        }
        if (isset($rawData['stock']['available'])) {
            $this->setQty($rawData['stock']['available']);
        }
        if (isset($rawData['sellingMode']['price']['amount'])) {
            $this->setPrice($rawData['sellingMode']['price']['amount']);
        }
        if (isset($rawData['publication']['status'])) {
            $this->setPublicationStatus($rawData['publication']['status']);
        }
        if (isset($rawData['delivery']['shippingRates']['id'])) {
            $this->setDeliveryShippingRatesId($rawData['delivery']['shippingRates']['id']);
        }
        if (isset($rawData['delivery']['handlingTime'])) {
            $this->setDeliveryHandlingTime($rawData['delivery']['handlingTime']);
        }
        if (isset($rawData['payments']['invoice'])) {
            $this->setPaymentsInvoice($rawData['payments']['invoice']);
        }

        $this->setParameters($this->mapParametersData($rawData['parameters'] ?? []));
        $this->setImages($this->mapImagesData($rawData['images'] ?? []));
        $this->setLocation($this->mapLocationData($rawData['location'] ?? []));
        $this->setValidationErrors($this->mapValidationErrorsData($rawData['validation']['errors'] ?? []));
        $this->setAfterSalesServices($this->mapAfterSalesServicesData($rawData['afterSalesServices'] ?? []));
    }

    /**
     * {@inheritDoc}
     */
    public function getRawData(): array
    {
        $rawData = [
            'id' => $this->getId() != '' ? $this->getId() : null,
            'name' => $this->getName(),
            'category' => [
                'id' => $this->getCategory(),
            ],
            'product' => null,
            'parameters' => $this->mapParameters($this->getParameters()),
            'description' => [
                'sections' => [
                    0 => [
                        'items' => [
                            0 => [
                                'type' => 'TEXT',
                                'content' => $this->getDescription(),
                            ]
                        ]
                    ]
                ]
            ],
            'compatibilityList' => null,
            'images' => $this->mapImages($this->getImages()),
            'sellingMode' => [
                'format' => 'BUY_NOW',
                'price' => [
                    'amount' => $this->getPrice(),
                    'currency' => 'PLN'
                ]
            ],
            'stock' => [
                'available' => $this->getQty(),
                'unit' => 'UNIT'
            ],
            'delivery' => [
                'shippingRates' => [
                    'id' => $this->getDeliveryShippingRatesId()
                ],
                'handlingTime' => $this->getDeliveryHandlingTime(),
                'additionalInfo' => null,
                'shipmentDate' => null
            ],
            'location' => $this->mapLocation($this->getLocation()),
            'payments' => [
                'invoice' => $this->getPaymentsInvoice()
            ],
            'afterSalesServices' => $this->mapAfterSalesServices($this->getAfterSalesServices())
        ];

        if ($this->getEan() != '') {
            $rawData['ean'] = $this->getEan();
        }

        return $rawData;
    }

    /**
     * @param array $parametersData
     * @return array
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    private function mapParametersData(array $parametersData): array
    {
        if (!$this->getCategory()) {
            return [];
        }

        $parameters = $this->parameterDefinitionRepository->createParametersByCategoryId($this->getCategory());

        $parametersValues = [];
        array_walk($parametersData, function ($parameterData) use (&$parametersValues) {
            $parametersValues[$parameterData['id']] = $parameterData;
        });

        $result = [];
        foreach ($parameters as $parameter) {
            $parameter->setRawData($parametersValues[$parameter->getId()] ?? []);
            $result[] = $parameter;
        }

        return $result;
    }

    /**
     * @param ParameterInterface[] $parameters
     * @return array
     */
    private function mapParameters(array $parameters): array
    {
        $result = [];
        foreach ($parameters as $parameter) {
            if ($parameter->isValueEmpty()) {
                continue;
            }
            $result[] = $parameter->getRawData();
        }
        return $result;
    }

    /**
     * @param array $imagesData
     * @return ImageInterface[]
     */
    private function mapImagesData(array $imagesData): array
    {
        $result = [];
        foreach ($imagesData as $imageData) {
            /** @var ImageInterface $image */
            $image = $this->imageFactory->create();
            $image->setRawData($imageData);
            $result[] = $image;
        }
        return $result;
    }

    /**
     * @param ImageInterface[] $images
     * @return array
     */
    private function mapImages(array $images): array
    {
        $result = [];
        foreach ($images as $image) {
            $result[] = $image->getRawData();
        }
        return $result;
    }

    /**
     * @param array $locationData
     * @return LocationInterface
     */
    private function mapLocationData(array $locationData): LocationInterface
    {
        /** @var LocationInterface $location */
        $location = $this->locationFactory->create();
        $location->setRawData($locationData);
        return $location;
    }

    /**
     * @param LocationInterface $location
     * @return array
     */
    private function mapLocation(LocationInterface $location): array
    {
        return $location->getRawData();
    }

    /**
     * @param array $data
     * @return string[]
     */
    private function mapValidationErrorsData(array $data): array
    {
        $result = [];
        foreach ($data as $error) {
            $result[] = $error['message'] ?: $error['userMessage'];
        }
        return $result;
    }

    /**
     * @param array $data
     * @return AfterSalesServicesInterface
     */
    private function mapAfterSalesServicesData(array $data): AfterSalesServicesInterface
    {
        /** @var AfterSalesServicesInterface $afterSalesServices */
        $afterSalesServices = $this->afterSalesServicesFactory->create();
        $afterSalesServices->setRawData($data);
        return $afterSalesServices;
    }

    /**
     * @param AfterSalesServicesInterface $afterSalesServices
     * @return array
     */
    private function mapAfterSalesServices(AfterSalesServicesInterface $afterSalesServices): ?array
    {
        return $afterSalesServices->getRawData();
    }
}
