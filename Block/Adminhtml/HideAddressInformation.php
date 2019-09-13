<?php

namespace Macopedia\Allegro\Block\Adminhtml;

use Magento\Sales\Block\Adminhtml\Order\View\Info;

/**
 *  class responsible for hiding address data in the order view
 */
class HideAddressInformation extends Info
{
    const ALLEGRO = 'Allegro';

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isSetShippingAddress()
    {
        $order = $this->getOrder();
        // TODO use ExtensionAttributesInterface
        if ($order->getData('order_from') === self::ALLEGRO) {
            $shippingAddress = $order->getShippingAddress();
            $firstName = $shippingAddress->getFirstname();
            $lastName = $shippingAddress->getLastname();
            if ($firstName === $lastName && $firstName === 'unknown') {
                return false;
            }
        }
        return true;
    }
}
