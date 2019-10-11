<?php

namespace Macopedia\Allegro\Ui\AllegroOffer\Form;

use Magento\Catalog\Model\Product;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

class CreateDataProvider extends DataProvider
{

    /** @var GetSalableQuantityDataBySku */
    protected $getSalableQuantityDataBySku;

    /** @var Registry */
    protected $registry;

    /**
     * CreateDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param GetSalableQuantityDataBySku $getSalableQuantityDataBySku
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        Registry $registry,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->registry = $registry;
    }

    /**
     * Get data
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__);
        if (isset($this->_loadedData)) {
            \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
            return $this->_loadedData;
        }

        /** @var Product $product */
        $product = $this->registry->registry('product');
        $stock = $this->getSalableQuantityDataBySku->execute($product->getSku());
        $images = $product->getMediaGalleryImages()->toArray();

        $this->_loadedData[$product->getId()] = [
            'allegro' => [
                'product' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'images' => isset($images['items']) ? $images['items'] : [],
                'qty' => $stock[0]['qty']
            ]
        ];

        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
        return $this->_loadedData;
    }
}
