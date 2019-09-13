<?php

namespace Macopedia\Allegro\Block\Adminhtml;

use Macopedia\Allegro\Model\ProductRepository;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Catalog\Model\Product;
use Magento\Framework\UrlInterface;

class ViewProductButton extends Container
{
    /** @var UrlInterface */
    private $urlBuilder;

    /** @var ProductRepository */
    private $productRepository;

    /** @var Product */
    private $product;

    /**
     * ViewProductButton constructor.
     * @param Context $context
     * @param UrlInterface $urlBuilder
     * @param ProductRepository $productRepository
     * @param array $data
     */
    public function __construct(
        Context $context,
        UrlInterface $urlBuilder,
        ProductRepository $productRepository,
        array $data = []
    ) {
        $this->_request = $context->getRequest();
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _construct()
    {
        $this->addButton(
            'preview_product',
            $this->getButtonData()
        );
        parent::_construct();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getButtonData()
    {
        $productId = $this->_request->getParam('id');
        $product = $this->productRepository->getById($productId);

        if ($product->getData('allegro_offer_id')) {
            $url = $this->urlBuilder->getUrl('allegro/offer/edit', ['id' => $product->getData('allegro_offer_id')]);
            $label = __('Edit Allegro Offer');
        } else {
            $url = $this->urlBuilder->getUrl('allegro/offer/create', ['product' => $product->getId()]);
            $label = __('Add to Allegro');
        }

        return [
            'label' => $label,
            'on_click' => sprintf("window.location='%s'", $url),
            'class' => 'view disable',
            'sort_order' => 20
        ];
    }
}
