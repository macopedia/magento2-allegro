<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer;

use Macopedia\Allegro\Model\Config\Source\DeliveryMethods;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

/**
 * Delivery Allegro shipping methods config source model
 */
class AllegroDeliveryMethods extends Select
{
    /** @var DeliveryMethods */
    private $deliveryMethods;

    /**
     * @param DeliveryMethods $deliveryMethods
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        DeliveryMethods $deliveryMethods,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->deliveryMethods = $deliveryMethods;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return Select
     */
    protected function _prepareLayout()
    {
        $this->setOptions(
            $this->deliveryMethods->toOptionArray()
        );
        return parent::_prepareLayout();
    }
}
