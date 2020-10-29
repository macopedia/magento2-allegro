<?php


namespace Macopedia\Allegro\Model;


use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;
use Magento\Framework\App\ResourceConnection;

class AllegroPrice
{
    /** @var Configuration */
    protected $config;

    /** @var Config */
    protected $eavConfig;

    /** @var ResourceConnection */
    protected $resource;

    /**
     * AllegroPrice constructor.
     * @param Configuration $config
     * @param Config $eavConfig
     * @param ResourceConnection $resource
     */
    public function __construct(
        Configuration $config,
        Config $eavConfig,
        ResourceConnection $resource
    ) {
        $this->config = $config;
        $this->eavConfig = $eavConfig;
        $this->resource = $resource;
    }

    /**
     * @param ProductInterface $product
     * @return float|int|mixed|null
     */
    public function get(ProductInterface $product)
    {
        $price = (float)$product->getData($this->getPriceAttributeCode());
        return $this->calculateNewPrice($price);
    }

    /**
     * @param string $productId
     * @param int $storeId
     * @return float|int
     * @throws AllegroPriceGettingException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByProductId(string $productId, int $storeId = 0)
    {
        $priceAttribute = $this->eavConfig->getAttribute(Product::ENTITY, $this->getPriceAttributeCode());

        $connection = $this->resource->getConnection();

        $select = $connection->select()
            ->from($priceAttribute->getBackendTable(), ['value'])
            ->where('entity_id = :productId')
            ->where('store_id = :storeId')
            ->where('attribute_id = :attributeId')
            ->where('value IS NOT NULL');

        $bind = [
            ':productId' => (int)$productId,
            ':storeId' => (int)$storeId,
            ':attributeId' => (int)$priceAttribute->getId()
        ];

        $price = $connection->fetchOne($select, $bind);
        if (is_null($price)) {
            throw new AllegroPriceGettingException(
                "Error while trying to get Allegro price for product with id {$productId}",
                1603885321
            );
        }

        return $this->calculateNewPrice((float)$price);
    }

    /**
     * @return string|null
     */
    protected function getPriceAttributeCode()
    {
        return $this->config->getPriceAttributeCode();;
    }

    /**
     * @param float $price
     * @return float|int
     */
    protected function calculateNewPrice(float $price)
    {
        if ($this->config->isPricePolicyEnabled()) {
            $percentIncrease = $this->config->getPricePercentIncrease();
            $price = $price + $price * $percentIncrease / 100;
        }

        return $price;
    }
}
