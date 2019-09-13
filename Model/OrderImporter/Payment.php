<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Config;

/**
 * Payment model class
 */
class Payment
{
    const ONLINE_PAYMENT_METHOD_CONFIG_KEY = 'allegro/payment/method_online';
    const CASH_ON_DELIVER_PAYMENT_METHOD_CONFIG_KEY = 'allegro/payment/method_cash_on_delivery';
    const DEFAULT_PAYMENT_METHOD = 'checkmo';
    const ONLINE_PAYMENT_TYPE = 'ONLINE';
    const CASH_ON_DELIVERY_PAYMENT_TYPE = 'CASH_ON_DELIVERY';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var Logger */
    private $logger;

    /** @var Config */
    private $paymentConfig;

    /** @var array */
    private $paymentMethods = [];

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Logger $logger
     * @param Config $paymentConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Logger $logger,
        Config $paymentConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @param string $paymentType
     * @return string
     */
    public function getPaymentMethodCode($paymentType)
    {
        if ($paymentType == '') {
            return $this->getDefaultMethodCode();
        }

        if (isset($this->paymentMethods[$paymentType])) {
            return $this->paymentMethods[$paymentType];
        }

        $paymentMethodCode = $this->getPaymentMethodCodeByPaymentType($paymentType);

        if ($this->validatePaymentMethod($paymentMethodCode)) {
            $this->paymentMethods[$paymentType] = $paymentMethodCode;
        } else {
            $this->paymentMethods[$paymentType] = $this->getDefaultMethodCode();
        }

        return $this->paymentMethods[$paymentType];
    }

    /**
     * @return string
     */
    public function getDefaultMethodCode()
    {
        return self::DEFAULT_PAYMENT_METHOD;
    }

    /**
     * @param $paymentType
     * @return string
     */
    private function getPaymentMethodCodeByPaymentType($paymentType)
    {
        $paymentMethodMap = [
            self::ONLINE_PAYMENT_TYPE => $this->scopeConfig->getValue(
                self::ONLINE_PAYMENT_METHOD_CONFIG_KEY
            ),
            self::CASH_ON_DELIVERY_PAYMENT_TYPE => $this->scopeConfig->getValue(
                self::CASH_ON_DELIVER_PAYMENT_METHOD_CONFIG_KEY
            )
        ];

        if (!isset($paymentMethodMap[$paymentType])) {
            return $this->getDefaultMethodCode();
        }

        $paymentMethodMap[$paymentType];
    }

    /**
     * @param string $code
     * @return bool
     */
    private function validatePaymentMethod($code)
    {
        foreach ($this->paymentConfig->getActiveMethods() as $paymentCode => $payment) {
            if ($paymentCode === $code) {
                return true;
            }
        }
        $this->logger->addError('Wrong payment method ' . $code);

        return false;
    }
}
