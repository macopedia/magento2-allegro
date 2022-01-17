<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\ResourceModel\Order\CheckoutForm;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order;

class AllegroOrderStatus
{
    const STATUSES_MAPPING_CONFIG_KEY = 'allegro/order/mapping';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var CheckoutForm
     */
    protected $checkoutForm;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param CheckoutForm $checkoutForm
     * @param Logger $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CheckoutForm $checkoutForm,
        Logger $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->checkoutForm = $checkoutForm;
        $this->logger = $logger;
    }

    /**
     * @param Order $order
     * @return void
     */
    public function updateOrderStatus(Order $order)
    {
        $statusesMapping = $this->scopeConfig->getValue(self::STATUSES_MAPPING_CONFIG_KEY);
        $statusesMapping = json_decode($statusesMapping, true);
        $statusesMapping = array_column($statusesMapping, 'allegro_code', 'magento_code');

        $magentoStatus = $order->getStatus();
        $checkoutFormId = $order->getExternalId() ?: $order->getExtensionAttributes()->getExternalId();
        if (!isset($statusesMapping[$magentoStatus]) || !$checkoutFormId) {
            return;
        }

        try {
            $this->checkoutForm->changeOrderStatus($checkoutFormId, $statusesMapping[$magentoStatus]);
            $this->logger->info('Status on Allegro for order ' . $order->getRemoteIp() . ' has been updated');
        } catch (\Exception $e) {
            $this->logger->exception(
                $e,
                'Error while trying to update order ' . $order->getIncrementId() . ' status on Allegro: ' . $e->getMessage()//phpcs:ignore
            );
        }
    }
}
