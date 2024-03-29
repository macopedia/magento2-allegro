<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class OffersMapping
{
    /** @var Offers */
    protected $offers;

    /** @var Logger */
    protected $logger;

    /** @var Configuration */
    protected $configuration;

    /** @var CollectionFactory */
    protected $productCollection;

    /** @var TokenProvider */
    protected $tokenProvider;

    /**
     * OffersMapping constructor.
     * @param Offers $offers
     * @param Logger $logger
     * @param Configuration $configuration
     * @param CollectionFactory $productCollection
     * @param TokenProvider $tokenProvider
     */
    public function __construct(
        Offers $offers,
        Logger $logger,
        Configuration $configuration,
        CollectionFactory $productCollection,
        TokenProvider $tokenProvider
    ) {
        $this->offers = $offers;
        $this->logger = $logger;
        $this->configuration = $configuration;
        $this->productCollection = $productCollection;
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @throws \Exception
     */
    public function clean()
    {
        //Check connection with Allegro
        $this->tokenProvider->getCurrent();

        $collection = $this->productCollection->create();
        $collection->addAttributeToSelect('*')
            ->addStoreFilter($this->configuration->getStoreId())
            ->addAttributeToFilter('allegro_offer_id', ['neq' => 'NULL']);

        /** @var Magento\Catalog\Model\Product $product */
        foreach ($collection->getItems() as $product) {
            $allegroOfferId = $product->getAllegroOfferId();
            try {
                $this->offers->get($allegroOfferId);
            } catch (Api\ClientResponseException $e) {
                if ($e->getCode() == 404) {
                    $product->setAllegroOfferId(null);
                    $product->save();
                }
            }
        }
    }
}
