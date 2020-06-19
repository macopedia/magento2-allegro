<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\Credentials;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Model\Order\Shipment;
use Macopedia\Allegro\Model\OrderImporter\OriginOfOrder;

/**
 * Checks if an order is paid before sending a shipment to Allegro
 */
class SaveShippingBeforeObserver implements ObserverInterface
{
    /** @var Logger */
    private $logger;

    /** @var Credentials */
    private $credentials;

    /** @var ManagerInterface */
    private $managerInterface;

    /** @var CheckoutForm */
    private $checkoutForm;

    /** @var OriginOfOrder */
    private $orderOrigin;

    /**
     * @param Logger $logger
     * @param Credentials $credentials
     * @param ManagerInterface $managerInterface
     * @param CheckoutForm $checkoutForm
     * @param OriginOfOrder $orderOrigin
     */
    public function __construct(
        Logger $logger,
        Credentials $credentials,
        ManagerInterface $managerInterface,
        CheckoutForm $checkoutForm,
        OriginOfOrder $orderOrigin
    ) {
        $this->logger = $logger;
        $this->credentials = $credentials;
        $this->managerInterface = $managerInterface;
        $this->checkoutForm = $checkoutForm;
        $this->orderOrigin = $orderOrigin;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {

            /** @var Shipment $shipment */
            $shipment = $observer->getEvent()->getShipment();

            $order = $shipment->getOrder();

            if (!$this->orderOrigin->isOrderFromAllegro($order)) {
                return;
            }

            $orderId = $order->getExternalId() ?: $order->getExtensionAttributes()->getExternalId();

            try {

                $this->credentials->getToken();

            } catch (ClientException $e) {
                $this->logger->exception($e);
                $this->managerInterface->addErrorMessage(
                    __('Can\'t set tracking information for this order - Allegro account is not connected. Connect to Allegro account and try again')//phpcs:ignore
                );
            }

            $orderData = $this->checkoutForm->getCheckoutForm($orderId);
            if (!isset($orderData['status']) || $orderData['status'] != 'READY_FOR_PROCESSING') {
                $this->logger->error("Error while saving shipping for order: Order $orderId is not yet paid");
                $this->managerInterface->addErrorMessage(
                    __('Can\'t set tracking information for this order - it is not yet paid')
                );
            }

        } catch (\Exception $e) {
            $this->logger->exception($e);
        }
    }
}
