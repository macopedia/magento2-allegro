<?php

namespace Macopedia\Allegro\Block\Adminhtml\Offer;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Back button configuration provider
 */
class BackButton implements ButtonProviderInterface
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * Registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
    }

    /**
     * Return button attributes array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'class' => 'action- scalable back',
            'on_click' => $this->getOnclick(),
            'sort_order' => 10,
        ];
    }

    /**
     * @return string
     */
    protected function getOnClick()
    {
        return sprintf(
            "location.href = '%s';",
            $this->urlBuilder->getUrl('catalog/product/edit', ['id' => $this->getProduct()->getId()])
        );
    }

    /**
     * @return ProductInterface
     */
    private function getProduct()
    {
        return $this->registry->registry('product');
    }
}
