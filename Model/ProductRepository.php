<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Gallery\MimeTypeExtensionMap;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Api\Data\ImageContentInterfaceFactory;
use Magento\Framework\Api\ImageContentValidatorInterface;
use Magento\Framework\Api\ImageProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\EntityManager\Operation\Read\ReadExtensions;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductRepository extends \Magento\Catalog\Model\ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var \Macopedia\Allegro\Model\ResourceModel\Product
     */
    protected $resourceModel;
    /** @var array */
    private $instancesByOfferId = [];

    /** @var int */
    private $cacheLimit = 0;

    public function __construct(
        ProductFactory $productFactory,
        \Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper $initializationHelper,
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $attributeRepository,
        \Macopedia\Allegro\Model\ResourceModel\Product $resourceModel,
        Product\Initialization\Helper\ProductLinks $linkInitializer,
        Product\LinkTypeProvider $linkTypeProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataServiceInterface,
        \Magento\Framework\Api\ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        Product\Option\Converter $optionConverter,
        \Magento\Framework\Filesystem $fileSystem,
        ImageContentValidatorInterface $contentValidator,
        ImageContentInterfaceFactory $contentFactory,
        MimeTypeExtensionMap $mimeTypeExtensionMap,
        ImageProcessorInterface $imageProcessor,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor = null,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        $cacheLimit = 1000,
        ReadExtensions $readExtensions = null
    ) {
        parent::__construct(
            $productFactory,
            $initializationHelper,
            $searchResultsFactory,
            $collectionFactory,
            $searchCriteriaBuilder,
            $attributeRepository,
            $resourceModel,
            $linkInitializer,
            $linkTypeProvider,
            $storeManager,
            $filterBuilder,
            $metadataServiceInterface,
            $extensibleDataObjectConverter,
            $optionConverter,
            $fileSystem,
            $contentValidator,
            $contentFactory,
            $mimeTypeExtensionMap,
            $imageProcessor,
            $extensionAttributesJoinProcessor,
            $collectionProcessor,
            $serializer,
            $cacheLimit,
            $readExtensions
        );

        $this->cacheLimit = $cacheLimit;
    }

    /**
     * @param int $allegroOfferId
     * @param bool $editMode
     * @param int $storeId
     * @param bool $forceReload
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function getByAllegroOfferId(
        int $allegroOfferId,
        bool $editMode = false,
        int $storeId = null,
        bool $forceReload = false
    ): ProductInterface {
        $cacheKey = $this->getCacheKey([$editMode, $storeId]);
        $cachedProduct = $this->getProductFromLocalCache($allegroOfferId, $cacheKey);
        if ($cachedProduct === null || $forceReload) {

            $productId = $this->resourceModel->getIdByAllegroOfferId($allegroOfferId);
            if (!$productId) {
                throw new NoSuchEntityException(
                    __("The product that was requested doesn't exist. Verify the product and try again.")
                );
            }
            return $this->getById($productId, $editMode, $storeId, $forceReload);
        }

        return $cachedProduct;
    }

    /**
     * @param string $allegroOfferId
     * @param string $cacheKey
     * @return Product|null
     */
    private function getProductFromLocalCache(string $allegroOfferId, string $cacheKey)
    {
        $preparedAllegroOfferId = $this->prepareAllegroOfferId($allegroOfferId);
        return $this->instancesByOfferId[$preparedAllegroOfferId][$cacheKey] ?? null;
    }

    /**
     * @param string $allegroOfferId
     * @return false|mixed|string|string[]|null
     */
    private function prepareAllegroOfferId(string $allegroOfferId)
    {
        return mb_strtolower(trim($allegroOfferId));
    }

    /**
     * @param string $cacheKey
     * @param ProductInterface $product
     */
    private function cacheProduct($cacheKey, ProductInterface $product)
    {
        $this->instancesById[$product->getId()][$cacheKey] = $product;
        $this->saveProductInLocalCache($product, $cacheKey);

        if ($this->cacheLimit && count($this->instances) > $this->cacheLimit) {
            $offset = round($this->cacheLimit / -2);
            $this->instancesById = array_slice($this->instancesById, $offset, null, true);
            $this->instances = array_slice($this->instances, $offset, null, true);
        }
    }

    /**
     * @param Product $product
     * @param string $cacheKey
     */
    private function saveProductInLocalCache(Product $product, string $cacheKey): void
    {
        $preparedAllegroOfferId = $this->prepareAllegroOfferId($product->getData('allegro_offer_id'));
        $this->instances[$preparedAllegroOfferId][$cacheKey] = $product;
    }

    /**
     * @param $productId
     * @return ProductInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getMinProductWithAllegro($productId): ?ProductInterface
    {
        $rawData = $this->resourceModel->getRawMinProductDataWithAllegro($productId);
        if ($rawData) {
            $product = $this->productFactory->create();
            $product->setData($rawData);
            return $product;
        }

        return null;
    }
}
