<?php

namespace Macopedia\Allegro\Ui\AllegroOffer\Form;

use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Categories;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;

class EditDataProvider extends DataProvider
{

    /** @var Categories */
    private $categories;

    /** @var Offers */
    private $offers;

    /** @var Registry */
    private $registry;

    /**
     * CreateDataProvider constructor.
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
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
        Categories $categories,
        Offers $offers,
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
        $this->categories = $categories;
        $this->offers = $offers;
        $this->registry = $registry;
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }

        /** @var OfferInterface $offer */
        $offer = $this->registry->registry('offer');

        /** @var ProductInterface $product */
        $product = $this->registry->registry('product');

        $parameters = [];
        if (count($offer->getParameters()) > 0) {
            foreach ($offer->getParameters() as $parameter) {
                $parameters[$parameter->getId()] = $parameter->getValue();
            }
        }

        $this->_loadedData[$offer['id']] = [
            'allegro' => [
                'id' => $offer->getId(),
                'product' => $product->getId(),
                'name' => $offer->getName(),
                'description' => $offer->getDescription(),
                'price' => $offer->getPrice(),
                'qty' => $offer->getQty(),
                'category' => $offer->getCategory(),
                'parameters' => $parameters,
            ],
        ];

        return $this->_loadedData;
    }
}
