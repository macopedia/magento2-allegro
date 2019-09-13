<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\Credentials;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\MessageQueue\ConnectionLostException;
use Magento\Sales\Model\Order\Shipment;

/**
 * Save shipping before observer
 */
class SaveShippingBeforeObserver implements ObserverInterface
{
    /** @var Logger */
    private $logger;

    /** @var Credentials */
    private $credentials;

    /** @var ManagerInterface */
    protected $managerInterface;

    /** @var CheckoutForm */
    protected $checkoutForm;

    /**
     * @param Logger $logger
     * @param Credentials $credentials
     * @param ManagerInterface $managerInterface
     * @param CheckoutForm $checkoutForm
     */
    public function __construct(
        Logger $logger,
        Credentials $credentials,
        ManagerInterface $managerInterface,
        CheckoutForm $checkoutForm
    ) {
        $this->logger = $logger;
        $this->credentials = $credentials;
        $this->managerInterface = $managerInterface;
        $this->checkoutForm = $checkoutForm;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws ConnectionLostException
     */
    public function execute(Observer $observer)
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        // TODO use ExtensionAttributesInterface
        $orderFrom = $shipment->getOrder()->getData('order_from');
        $orderId = $shipment->getOrder()->getData('external_id');
        
        if ($orderFrom == 'Allegro') {

            try {
                $token = $this->credentials->getToken();
            } catch (ClientException $e) {
                $this->logger->error("Error while receiving token from Allegro");
                $this->managerInterface->addErrorMessage(
                    __('Can\'t set tracking information for this order - Allegro account is not connected. Connect to Allegro account and try again')
                );
                throw new ConnectionLostException('Error while receiving token from Allegro', $e);
            }
            $orderData = $this->checkoutForm->getCheckoutForm($orderId);
            if (!isset($orderData['status']) || $orderData['status'] != 'READY_FOR_PROCESSING') {
                $this->logger->error("Error while saving shipping for order: Order $orderId is not yet paid");
                $this->managerInterface->addErrorMessage(
                    __('Can\'t set tracking information for this order - it is not yet paid')
                );
                throw new ConnectionLostException(
                    "Error while saving shipping for order: Order $orderId is not yet paid"
                );
            }
        }
    }
}
