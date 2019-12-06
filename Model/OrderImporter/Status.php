<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\Order;

/**
 * Class responsible for get order status depends on order data received from Allegro API
 */
class Status
{
    const PENDING_STATUS = 'pending';
    const PROCESSING_STATUS = 'processing';
    const OVERPAYMENT_STATUS_CONFIG_KEY = 'allegro/order/overpayment_status';
    const UNDERPAYMENT_STATUS_CONFIG_KEY = 'allegro/order/overpayment_status';
    const STATUS_KEY = 'status';
    const STATE_KEY = 'state';
    const PAID_KEY = 'paid';
    const STATUS_STATE_SEPARATOR = ':';
    const ALLEGRO_READY_FOR_PROCESSING = "READY_FOR_PROCESSING";

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @return array
     */
    public function get(CheckoutFormInterface $checkoutForm)
    {
        $paidAmountValue = $checkoutForm->getPayment()->getPaidAmount()->getAmount();
        if ($paidAmountValue == 0.) {
            return $this->arrayResponse(self::PENDING_STATUS, Order::STATE_NEW);
        }

        $totalPaidValue = $checkoutForm->getSummary()->getTotalToPay()->getAmount();
        if ($paidAmountValue === $totalPaidValue && $checkoutForm->getStatus() == self::ALLEGRO_READY_FOR_PROCESSING) {
            return $this->arrayResponse(self::PROCESSING_STATUS, Order::STATE_PROCESSING, true);
        }

        if ($paidAmountValue > $totalPaidValue) {
            return [self::PENDING_STATUS, $this->getOverpaymentStatus()];
        }

        return [self::PENDING_STATUS, $this->getUnderpaymentStatus()];
    }

    /**
     * @return array
     */
    private function getOverpaymentStatus()
    {
        return $this->getStatusFromConfig(self::OVERPAYMENT_STATUS_CONFIG_KEY);
    }

    /**
     * @return array
     */
    private function getUnderpaymentStatus()
    {
        return $this->getStatusFromConfig(self::UNDERPAYMENT_STATUS_CONFIG_KEY);
    }

    /**
     * @param string $key
     * @return array
     */
    private function getStatusFromConfig($key)
    {
        $value = $this->scopeConfig->getValue($key);
        if (!$value) {
            return [self::STATUS_KEY => self::PENDING_STATUS, self::STATE_KEY => Order::STATE_NEW];
        }
        list($status, $state) = explode(self::STATUS_STATE_SEPARATOR, $value);

        return [self::STATUS_KEY => $status, self::STATE_KEY => $state];
    }

    /**
     * @param string $status
     * @param string $state
     * @param bool $paid
     * @return array
     */
    private function arrayResponse($status, $state, $paid = false)
    {
        return [
            self::STATUS_KEY => $status,
            self::STATE_KEY => $state,
            self::PAID_KEY => $paid
        ];
    }
}
