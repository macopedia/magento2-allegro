<?php

namespace Macopedia\Allegro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Configuration
{

    const STOCK_SYNCHRONIZATION_ENABLED_CONFIG_PATH = 'allegro/order/stock_synchronization_enabled';
    const TRACKING_NUMBER_SENDING_ENABLED_CONFIG_PATH = 'allegro/order/tracking_number_sending_enabled';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isStockSynchronizationEnabled(string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, ?string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::STOCK_SYNCHRONIZATION_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isTrackingNumberSendingEnabled(string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, ?string $scopeCode = null): bool
    {
        return $this->scopeConfig->isSetFlag(self::TRACKING_NUMBER_SENDING_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }
}
