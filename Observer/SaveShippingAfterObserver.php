<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order\Shipment;

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

    /**
     * @param Product $productResource
     * @param CheckoutForm $checkoutFrom
     * @param Logger $logger
     */
    public function __construct(
        Product $productResource,
        CheckoutForm $checkoutFrom,
        Logger $logger
    ) {
        $this->productResource = $productResource;
        $this->checkoutFrom = $checkoutFrom;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Shipment $shipment */
        $shipment = $observer->getEvent()->getShipment();

        // TODO use ExtensionAttributesInterface
        $orderId = $shipment->getOrder()->getData('external_id');
        $orderFrom = $shipment->getOrder()->getData('order_from');
        
        if ($orderFrom == 'Allegro') {
            $shipmentData = ['lineItems' => []];
            foreach ($shipment->getItems() as $item) {
                $allegroId = $item->getOrderItem()->getData('allegro_line_item_id');
                if (!$allegroId) {
                    continue;
                }
                $shipmentData['lineItems'][] = ['id' => $allegroId];
            }

            foreach ($shipment->getTracks() as $shipmentTrack) {
                $carrierCode = $shipmentTrack->getCarrierCode();
                $trackNumber = $shipmentTrack->getTrackNumber();
                if ($trackNumber) {
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
    }
}
