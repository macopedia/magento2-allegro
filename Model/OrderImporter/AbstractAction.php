<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

abstract class AbstractAction
{

    /** @var Status */
    protected $status;

    /** @var Invoice */
    protected $invoice;

    /** @var Shipping */
    protected $shipping;

    /** @var Payment */
    protected $payment;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /**
     * @param Shipping $shipping
     * @param Payment $payment
     * @param Status $status
     * @param Invoice $invoice
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Shipping $shipping,
        Payment $payment,
        Status $status,
        Invoice $invoice,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->shipping = $shipping;
        $this->payment = $payment;
        $this->status = $status;
        $this->invoice = $invoice;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processStatus(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $status = $this->status->get($checkoutForm);

        if ($status[Status::STATE_KEY] != Order::STATE_NEW) {
            $order
                ->setStatus($status[Status::STATUS_KEY])
                ->setState($status[Status::STATE_KEY]);
        }

        if ($status[Status::PAID_KEY]) {
            $this->invoice->create($order);
        }
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processTotals(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $shippingAmount = $order->getShippingAmount();
        $deliveryCostAmount = $checkoutForm->getDelivery()->getCost()->getAmount();

        $order->setShippingAmount($deliveryCostAmount);
        $order->setBaseShippingAmount($deliveryCostAmount);
        $order->setBaseGrandTotal($order->getBaseGrandTotal() + $deliveryCostAmount - $shippingAmount);
        $order->setGrandTotal($order->getGrandTotal() + $deliveryCostAmount - $shippingAmount);
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processComments(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $messageToSeller = $checkoutForm->getMessageToSeller();
        if ($messageToSeller == null) {
            return;
        }

        $statusHistories = $order->getStatusHistories();
        if (array_search($messageToSeller, $statusHistories) === false) {
            $order->addStatusHistoryComment($messageToSeller);
        }
    }
}
