<?php

namespace Macopedia\Allegro\Block\Adminhtml\Offer;

use Macopedia\Allegro\Api\Data\OfferInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Publish button class
 */
class EndButton implements ButtonProviderInterface
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
            'label' => __('End offer'),
            'class' => 'action-secondary',
            'disabled' => !$this->getOffer()->canBeEnded(),
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
            $this->urlBuilder->getUrl('allegro/offer/end/', ['id' => $this->getOffer()->getId()])
        );
    }

    /**
     * @return OfferInterface
     */
    private function getOffer()
    {
        return $this->registry->registry('offer');
    }
}
