<?php

namespace Macopedia\Allegro\Plugin;

use Macopedia\Allegro\Model\PickupPoint;
use Macopedia\Allegro\Model\PickupPointFactory;
use Magento\Sales\Api\OrderRepositoryInterface as Subject;
use Magento\Sales\Api\Data\OrderInterface;

class OrderSave
{

    /** @var PickupPointFactory */
    private $pickupPointFactory;

    /**
     * OrderSave constructor.
     * @param PickupPointFactory $pickupPointFactory
     */
    public function __construct(PickupPointFactory $pickupPointFactory)
    {
        $this->pickupPointFactory = $pickupPointFactory;
    }

    /**
     * @param Subject $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @throws \Exception
     */
    public function afterSave(Subject $subject, OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if ($extensionAttributes === null || $extensionAttributes->getPickupPoint() === null) {
            return $order;
        }

        $pickupPointExtensionAttributes = $extensionAttributes->getPickupPoint();
        if ($pickupPointExtensionAttributes === null) {
            return $order;
        }

        /** @var PickupPoint $pickupPoint */
        $pickupPoint = $this->pickupPointFactory->create();
        $pickupPoint->load($order->getEntityId(), 'order_id');
        $pickupPoint->addData([
            'order_id' => $order->getEntityId(),
            'point_id' => $pickupPointExtensionAttributes->getPointId(),
            'name' => $pickupPointExtensionAttributes->getName(),
            'description' => $pickupPointExtensionAttributes->getDescription(),
            'street' => $pickupPointExtensionAttributes->getStreet(),
            'zip_code' => $pickupPointExtensionAttributes->getZipCode(),
            'city' => $pickupPointExtensionAttributes->getCity(),
        ]);
        $pickupPoint->save();

        return $order;
    }
}
