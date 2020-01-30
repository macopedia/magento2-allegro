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
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
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
        \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__);
        if (isset($this->_loadedData)) {
            \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
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
                'ean' => $offer->getEan(),
                'description' => $offer->getDescription(),
                'images' => $offer->getImages(),
                'delivery_shipping_rates_id' => $offer->getDeliveryShippingRatesId(),
                'implied_warranty' => $offer->getAfterSalesServices()->getImpliedWarrantyId(),
                'return_policy' => $offer->getAfterSalesServices()->getReturnPolicyId(),
                'warranty' => $offer->getAfterSalesServices()->getWarrantyId(),
                'delivery_handling_time' => $offer->getDeliveryHandlingTime(),
                'payments_invoice' => $offer->getPaymentsInvoice(),
                'price' => $offer->getPrice(),
                'qty' => $offer->getQty(),
                'category' => $offer->getCategory(),
                'parameters' => $parameters,
            ],
        ];

        \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);
        return $this->_loadedData;
    }
}
