<?php

namespace Macopedia\Allegro\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Product stock change observer
 */
class QtyChangeObserver implements ObserverInterface
{
    /**
     * @var \Macopedia\Allegro\Service\MessageQtyChange
     */
    protected $messageQtyChange;

    /**
     * QtyChangeObserver constructor.
     * @param \Macopedia\Allegro\Service\MessageQtyChange $messageQtyChange
     */
    public function __construct(
        \Macopedia\Allegro\Service\MessageQtyChange $messageQtyChange
    ) {
        $this->messageQtyChange = $messageQtyChange;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $productId = $observer->getEvent()->getData('item')->getProductId();
        $this->messageQtyChange->execute($productId);
    }
}
