<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Payment\Model\Config;
use Magento\Store\Model\ScopeInterface;

/**
 * Active payment methods config source model
 */
class ActivePaymentMethods implements OptionSourceInterface
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var Config */
    protected $paymentConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $paymentConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Config $paymentConfig)
    {
        $this->scopeConfig = $scopeConfig;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $methods = [['value' => '', 'label' => '']];
        $payments = $this->paymentConfig->getActiveMethods();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->scopeConfig->getValue(
                'payment/' . $paymentCode . '/title',
                ScopeInterface::SCOPE_STORE
            );

            $methods[$paymentCode] = [
                'label' => $paymentTitle,
                'value' => $paymentCode
            ];
        }

        return $methods;
    }
}
