<?php


namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\Data\ImageInterfaceFactory;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Api\Data\PublicationInterfaceFactory;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;
use Magento\Framework\DataObject;

class Offer extends DataObject implements OfferInterface
{

    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const DESCRIPTION_FIELD_NAME = 'description';
    const QTY_FIELD_NAME = 'qty';
    const PRICE_FIELD_NAME = 'price';
    const CATEGORY_FIELD_NAME = 'category';
    const PARAMETERS_FIELD_NAME = 'parameters';
    const IMAGES_FIELD_NAME = 'images';
    const LOCATION_FIELD_NAME = 'location';
    const PAYMENTS_FIELD_NAME = 'payments';
    const DELIVERY_FIELD_NAME = 'delivery';
    const PUBLICATION_STATUS_FIELD_NAME = 'publication_status';

    /** @var ParameterDefinitionRepositoryInterface */
    private $parameterDefinitionRepository;

    /** @var ImageInterfaceFactory */
    private $imageFactory;

    /**
     * Offer constructor.
     * @param ParameterDefinitionRepositoryInterface $parameterDefinitionRepository
     * @param ImageInterfaceFactory $imageFactory
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    public function __construct(
        ParameterDefinitionRepositoryInterface $parameterDefinitionRepository,
        ImageInterfaceFactory $imageFactory
    ) {
        $this->parameterDefinitionRepository = $parameterDefinitionRepository;
        $this->imageFactory = $imageFactory;
        $this->setRawData([]);
    }

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
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->setData(self::DESCRIPTION_FIELD_NAME, $description);
    }

    /**
     * @param string $category
     * @return void
     */
    public function setCategory(string $category)
    {
        $this->setData(self::CATEGORY_FIELD_NAME, $category);
    }

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty)
    {
        $this->setData(self::QTY_FIELD_NAME, $qty);
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price)
    {
        $this->setData(self::PRICE_FIELD_NAME, $price);
    }

    /**
     * @param ParameterInterface[] $parameters
     * @return void
     */
    public function setParameters(array $parameters)
    {
        $this->setData(self::PARAMETERS_FIELD_NAME, $parameters);
    }

    /**
     * @param ImageInterface[] $images
     * @return void
     */
    public function setImages(array $images)
    {
        $this->setData(self::IMAGES_FIELD_NAME, $images);
    }

    /**
     * @param array $location
     */
    public function setLocation(array $location)
    {
        $this->setData(self::LOCATION_FIELD_NAME, $location);
    }

    /**
     * @param array $payments
     */
    public function setPayments(array $payments)
    {
        $this->setData(self::PAYMENTS_FIELD_NAME, $payments);
    }

    /**
     * @param array $delivery
     */
    public function setDelivery(array $delivery)
    {
        $this->setData(self::DELIVERY_FIELD_NAME, $delivery);
    }

    /**
     * @param string $publicationStatus
     */
    public function setPublicationStatus(string $publicationStatus)
    {
        $this->setData(self::PUBLICATION_STATUS_FIELD_NAME, $publicationStatus);
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
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->getData(self::DESCRIPTION_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getCategory(): ?string
    {
        return $this->getData(self::CATEGORY_FIELD_NAME);
    }

    /**
     * @return int
     */
    public function getQty(): ?int
    {
        return $this->getData(self::QTY_FIELD_NAME);
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->getData(self::PRICE_FIELD_NAME);
    }

    /**
     * @return ParameterInterface[]
     */
    public function getParameters(): array
    {
        return (array) $this->getData(self::PARAMETERS_FIELD_NAME);
    }

    /**
     * @return ImageInterface[]
     */
    public function getImages(): array
    {
        return (array) $this->getData(self::IMAGES_FIELD_NAME);
    }

    /**
     * @return array|null
     */
    public function getLocation(): array
    {
        return $this->getData(self::LOCATION_FIELD_NAME);
    }

    /**
     * @return array|null
     */
    public function getPayments(): array
    {
        return $this->getData(self::PAYMENTS_FIELD_NAME);
    }

    /**
     * @return array|null
     */
    public function getDelivery(): array
    {
        return $this->getData(self::DELIVERY_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getPublicationStatus(): ?string
    {
        return $this->getData(self::PUBLICATION_STATUS_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
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

        $this->setParameters($this->mapParametersData($rawData['parameters']?? []));
        $this->setImages($this->mapImagesData($rawData['images'] ?? []));
        $this->setLocation($rawData['location'] ?? []);
        $this->setPayments($rawData['payments'] ?? []);
        $this->setDelivery($rawData['delivery'] ?? []);
    }

    /**
     * @return array
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
            'ean' => null,
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
        ];

        if (!empty($this->getLocation())) {
            $rawData['location'] = $this->getLocation();
        }

        if (!empty($this->getPayments())) {
            $rawData['payments'] = $this->getPayments();
        }

        if (!empty($this->getDelivery())) {
            $rawData['delivery'] = $this->getDelivery();
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
            $parameter->setRawData($parametersValues[$parameter->getId()]);
            $result[] = $parameter;
        }

        return $result;
    }

    /**
     * @param ParameterInterface[] $parameters
     * @return array
     */
    private function mapParameters(array $parameters)
    {
        $result = [];
        foreach ($parameters as $parameter) {
            $result[] =  $parameter->getRawData();
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
}
