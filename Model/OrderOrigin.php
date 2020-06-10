<?php


namespace Macopedia\Allegro\Model;


use Magento\Sales\Model\Order;

/**
 * Class checks if order is from Allegro
 */
class OrderOrigin
{
    /**
     * @param Order $order
     * @return bool
     */
    public function isOrderFromAllegro(Order $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes && stripos($extensionAttributes->getOrderFrom(), 'Allegro') !== false) {
            return true;
        }

        return false;
    }
}
