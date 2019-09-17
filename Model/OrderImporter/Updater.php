<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * Magento order updater
 */
class Updater extends AbstractAction
{

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     * @throws BillingAddressIdException
     * @throws ShippingAddressIdException
     */
    public function execute(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $this->processShipping($order, $checkoutForm);
        $this->processPayment($order, $checkoutForm);
        $this->processTotals($order, $checkoutForm);
        $this->processStatus($order, $checkoutForm);
        $this->processComments($order, $checkoutForm);

        $checkoutForm->getDelivery()->getPickupPoint()->fillOrder($order);

        $this->orderRepository->save($order);
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     * @throws ShippingAddressIdException
     */
    private function processShipping(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $shippingAddress = $order->getShippingAddress();
        if (!$shippingAddress->getId()) {
            throw new ShippingAddressIdException('Can\'t get shipping address Id');
        }

        $checkoutForm->getDelivery()->getAddress()->fillAddress($shippingAddress);
        $shippingMethodCode = $this->shipping->getShippingMethodCode($checkoutForm);

        $shippingAddress->setShippingMethod($shippingMethodCode);
        $order->getExtensionAttributes()->setShippingAssignments(null);
        $order->setShippingMethod($shippingMethodCode);
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     * @throws BillingAddressIdException
     */
    private function processPayment(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $billingAddress = $order->getBillingAddress();
        if (!$billingAddress->getId()) {
            throw new BillingAddressIdException('Can\'t get billing address Id');
        }

        $checkoutForm->getInvoice()->getAddress()->fillAddress(
            $billingAddress,
            $checkoutForm->getDelivery()->getAddress()
        );

        $order->getPayment()->setMethod(
            $this->payment->getPaymentMethodCode(
                $checkoutForm
            )
        );
    }
}
