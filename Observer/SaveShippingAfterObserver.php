<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Configuration;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Save shipping after observer
 */
class SaveShippingAfterObserver implements ObserverInterface
{
    /** @var array */
    private static $availableCarriers = [
        'ups' => 'UPS',
        'inpost' => 'INPOST',
        'dhl' => 'DHL',
        'gls' => 'GLS',
        'ruch' => 'RUCH',
        'poczta_polska' => 'POCZTA_POLSKA',
        'dpd' => 'DPD',
        'pocztex' => 'POCZTEX',
        'fedex' => 'FEDEX',
        'tnt_express' => 'TNT_EXPRESS',
        'db_schenker' => 'DB_SCHENKER',
        'raben' => 'RABEN',
        'geis' => 'GEIS',
        'dts' => 'DTS'
    ];

    /** @var Product */
    private $productResource;

    /** @var CheckoutForm */
    private $checkoutFrom;

    /** @var Logger */
    private $logger;

    /** @var Configuration */
    private $config;

    /**
     * SaveShippingAfterObserver constructor.
     * @param Product $productResource
     * @param CheckoutForm $checkoutFrom
     * @param Logger $logger
     * @param Configuration $config
     */
    public function __construct(
        Product $productResource,
        CheckoutForm $checkoutFrom,
        Logger $logger,
        Configuration $config
    ) {
        $this->productResource = $productResource;
        $this->checkoutFrom = $checkoutFrom;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (!$this->config->isTrackingNumberSendingEnabled()) {
            return;
        }

        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        // TODO use ExtensionAttributesInterface
        $orderId = $shipment->getOrder()->getData('external_id');
        $orderFrom = $shipment->getOrder()->getData('order_from');

        if ($orderFrom != 'Allegro') {
            return;
        }

        $shipmentData = ['lineItems' => []];
        foreach ($shipment->getItems() as $item) {
            $allegroId = $item->getOrderItem()->getData('allegro_line_item_id');
            if (!$allegroId) {
                continue;
            }
            $shipmentData['lineItems'][] = ['id' => $allegroId];
        }

        foreach ($shipment->getTracks() as $shipmentTrack) {
            $trackNumber = $shipmentTrack->getTrackNumber();
            if (!$trackNumber) {
                continue;
            }

            $carrierCode = $shipmentTrack->getCarrierCode();
            if (isset(self::$availableCarriers[$carrierCode])) {
                $shipmentData['carrierId'] = self::$availableCarriers[$carrierCode];
            } else {
                $shipmentData['carrierId'] = 'OTHER';
                $shipmentData['carrierName'] = $carrierCode;
            }
            $shipmentData['waybill'] = $trackNumber;

            try {
                $this->checkoutFrom->shipment($orderId, $shipmentData);
            } catch (\Exception $exception) {
                $this->logger->exception($exception);
            }
        }
    }
}
