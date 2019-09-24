<?php

namespace Macopedia\Allegro\Block\Adminhtml\Offer;

use Macopedia\Allegro\Api\Data\OfferInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Publish button class
 */
class PublishButton implements ButtonProviderInterface
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
            'label' => $this->getLabel(),
            'class' => implode( ' ', $this->getClasses()),
            'on_click' => $this->getOnclick(),
            'sort_order' => 10,
        ];
    }

    /**
     * @return string
     */
    protected function getLabel()
    {
        if ($this->getOffer()->getPublicationStatus() === OfferInterface::PUBLICATION_STATUS_ENDED) {
            return __('Resume offer');
        }
        if ($this->getOffer()->getPublicationStatus() === OfferInterface::PUBLICATION_STATUS_INACTIVE) {
            return __('Publish offer');
        }
        return '';
    }

    /**
     * @return string
     */
    protected function getOnClick()
    {
        return sprintf(
            "location.href = '%s';",
            $this->urlBuilder->getUrl('allegro/offer/publish/', ['id' => $this->getOffer()->getId()])
        );
    }

    /**
     * @return string[]
     */
    protected function getClasses()
    {
        $classess = ['action-secondary'];

        if (!$this->getOffer()->canBePublished()) {
            $classess[] = 'hidden';
        }

        return $classess;
    }

    /**
     * @return OfferInterface
     */
    private function getOffer()
    {
        return $this->registry->registry('offer');
    }
}
