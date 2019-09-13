<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Logger\Logger;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Product model class
 */
class Product
{
    const ALLEGRO_OFFER_ID = 'allegro_offer_id';

    /** @var ProductFactory */
    protected $productFactory;

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var Logger */
    protected $logger;

    /**
     * @param ProductFactory $productFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     */
    public function __construct(
        ProductFactory $productFactory,
        ScopeConfigInterface $scopeConfig,
        Logger $logger
    ) {
        $this->productFactory = $productFactory;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * @param array $offerData
     * @return \Magento\Catalog\Model\Product | bool
     * @throws \Exception
     */
    public function get(array $offerData)
    {
        if (!isset($offerData['id'])) {
            $this->logger->error("Missing offer id in offer data");
            return false;
        }

        $attributeCode = self::ALLEGRO_OFFER_ID;
        $attributeValue = $offerData['id'];

        $product = $this->productFactory->create()
            ->loadByAttribute($attributeCode, $attributeValue);

        if (!$product || !$product->getId()) {
            $this->logger->error("Product with allegro_offer_id equals $attributeValue not found");
            return false;
        }

        return $product;
    }
}
