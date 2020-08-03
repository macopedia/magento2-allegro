<?php

namespace Macopedia\Allegro\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Observer copying fields from quote table to order table
 */
class CopyFieldsFromQuoteToOrderObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;

    /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     * ...
     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService
    ) {
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return CopyFieldsFromQuoteToOrderObserver
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');

        $cartExtensions = $quote->getExtensionAttributes();
        if ($cartExtensions) {
            $order->setData('external_id', $cartExtensions->getExternalId());
            $order->setData('order_from', $cartExtensions->getOrderFrom());
            $order->setData('allegro_buyer_login', $cartExtensions->getAllegroBuyerLogin());
        }

        $this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);

        return $this;
    }
}
