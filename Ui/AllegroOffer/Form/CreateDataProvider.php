<?php

namespace Macopedia\Allegro\Ui\AllegroOffer\Form;

use Macopedia\Allegro\Model\Configuration;
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

    /** @var Configuration */
    protected $config;

    /**
     * CreateDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param GetSalableQuantityDataBySku $getSalableQuantityDataBySku
     * @param Registry $registry
     * @param Configuration $config
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
        Configuration $config,
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
        $this->config = $config;
    }

    /**
     * @param Product $product
     * @return string
     */
    protected function getAllegroImage(Product $product)
    {
        if ($product->getAllegroImage() && $product->getAllegroImage() !== 'no_selection') {
            return $product->getAllegroImage();
        }

        return $product->getImage();
    }

    /**
     * Get data
     *
     * @return array
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
        $allegroImage =  $this->getAllegroImage($product);
        foreach ($images['items'] as $key => $image) {
            if ($image['file'] !== $allegroImage) {
                unset($images['items'][$key]);
            }
        }

        $eanAttributeCode = $this->config->getEanAttributeCode();
        $descriptionAttributeCode = $this->config->getDescriptionAttributeCode();

        $this->_loadedData[$product->getId()] = [
            'allegro' => [
                'product' => $product->getId(),
                'ean' => $eanAttributeCode ? $product->getData($eanAttributeCode) : '',
                'name' => $product->getName(),
                'description' => $descriptionAttributeCode
                    ? $product->getData($descriptionAttributeCode)
                    : $product->getDescription(),
                'price' => $product->getPrice(),
                'images' => isset($images['items']) ? $images['items'] : [],
                'qty' => $stock[0]['qty']
            ]
        ];

        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
        return $this->_loadedData;
    }
}
