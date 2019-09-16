<?php

namespace Macopedia\Allegro\Plugin;

use Macopedia\Allegro\Api\Data\Sales\Order\PickupPointExtensionAttributesInterface;
use Macopedia\Allegro\Api\Data\Sales\Order\PickupPointExtensionAttributesInterfaceFactory;
use Macopedia\Allegro\Model\PickupPoint;
use Macopedia\Allegro\Model\PickupPointFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface as Subject;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class OrderGet
{

    /** @var OrderExtensionFactory */
    private $orderExtensionFactory;

    /** @var PickupPointFactory */
    private $pickupPointFactory;

    /** @var PickupPointExtensionAttributesInterfaceFactory */
    private $pickupPointExtAttrsFactory;

    /**
     * OrderGet constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param PickupPointFactory $pickupPointFactory
     * @param PickupPointExtensionAttributesInterfaceFactory $pickupPointExtAttrsFactory
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        PickupPointFactory $pickupPointFactory,
        PickupPointExtensionAttributesInterfaceFactory $pickupPointExtAttrsFactory
    ) {
        $this->orderExtensionFactory = $orderExtensionFactory;
        $this->pickupPointFactory = $pickupPointFactory;
        $this->pickupPointExtAttrsFactory = $pickupPointExtAttrsFactory;
    }

    /**
     * @param Subject $subject
     * @param OrderInterface $order
     * @return OrderInterface
     */
    public function afterGet(Subject $subject, OrderInterface $order)
    {
        /** @var PickupPoint $pickupPoint */
        $pickupPoint = $this->pickupPointFactory->create();
        $pickupPoint->load($order->getId(), 'order_id');

        if (!$pickupPoint->getId()) {
            return  $order;
        }

        $extAttrs = $order->getExtensionAttributes();
        if ($extAttrs === null) {
            $extAttrs = $this->orderExtensionFactory->create();
        }

        /** @var PickupPointExtensionAttributesInterface $pickupPointExtAttrs */
        $pickupPointExtAttrs = $this->pickupPointExtAttrsFactory->create();
        $pickupPointExtAttrs->setPointId($pickupPoint->getData('point_id'));
        $pickupPointExtAttrs->setName($pickupPoint->getData('name'));
        $pickupPointExtAttrs->setDescription($pickupPoint->getData('description'));
        $pickupPointExtAttrs->setStreet($pickupPoint->getData('street'));
        $pickupPointExtAttrs->setZipCode($pickupPoint->getData('zip_code'));
        $pickupPointExtAttrs->setCity($pickupPoint->getData('city'));
        $extAttrs->setPickupPoint($pickupPointExtAttrs);

        $order->setExtensionAttributes($extAttrs);
        return $order;
    }
}
