<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Model\OrderImporter\OriginOfOrder;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Macopedia\Allegro\Model\AllegroOrderStatus;

class SaveOrderAfterObserver implements ObserverInterface
{
    /**
     * @var OriginOfOrder
     */
    protected $orderOrigin;

    /**
     * @var AllegroOrderStatus
     */
    protected $allegroOrderStatus;

    /**
     * @param OriginOfOrder $orderOrigin
     * @param AllegroOrderStatus $allegroOrderStatus
     */
    public function __construct(
        OriginOfOrder $orderOrigin,
        AllegroOrderStatus $allegroOrderStatus
    ) {
        $this->orderOrigin = $orderOrigin;
        $this->allegroOrderStatus = $allegroOrderStatus;
    }

    /**
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        if (!$order->getId() || !$this->orderOrigin->isOrderFromAllegro($order)) {
            return $this;
        }
        $this->allegroOrderStatus->updateOrderStatus($order);

        return $this;
    }
}
