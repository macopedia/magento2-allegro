<?php

namespace Macopedia\Allegro\Block;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;

/**
 * Origin of the order data class
 */
class OriginOrderData extends Template
{
    /**
     * @var Template\Context
     */
    private $context;
    /**
     * @var Registry
     */
    private $registry;

    /* @var \Magento\Sales\Model\Order */
    private $order;

    /**
     * OriginOrderData constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->context = $context;
        $this->registry = $registry;
        $this->order = $this->registry->registry('sales_order');
    }

    /**
     * @return mixed
     */
    public function getOrderOrigin()
    {
        // TODO use ExtensionAttributesInterface
        return $this->order->getData('order_from');
    }

    /**
     * @return mixed
     */
    public function getExternalID()
    {
        // TODO use ExtensionAttributesInterface
        return $this->order->getData('external_id');
    }

    /**
     * @return mixed
     */
    public function getBuyerLogin()
    {
        return $this->order->getData('allegro_buyer_login');
    }
}
