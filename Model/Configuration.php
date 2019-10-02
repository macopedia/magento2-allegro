<?php

namespace Macopedia\Allegro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\FlagManager;

class Configuration
{

    const STOCK_SYNCHRONIZATION_ENABLED_CONFIG_PATH = 'allegro/order/stock_synchronization_enabled';
    const TRACKING_NUMBER_SENDING_ENABLED_CONFIG_PATH = 'allegro/order/tracking_number_sending_enabled';
    const LAST_EVENT_ID_FLAG_NAME = 'allegro_order_last_event_id';
    const LAST_USER_ID_FLAG_NAME = 'allegro_credentials_last_user_id';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var FlagManager */
    private $flagManager;

    /**
     * Configuration constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param FlagManager $flagManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        FlagManager $flagManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->flagManager = $flagManager;
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

    /**
     * @return string|null
     */
    public function getLastEventId(): ?string
    {
        return $this->flagManager->getFlagData(self::LAST_EVENT_ID_FLAG_NAME);
    }

    /**
     * @return string|null
     */
    public function getLastUserId(): ?string
    {
        return $this->flagManager->getFlagData(self::LAST_USER_ID_FLAG_NAME);
    }

    /**
     * @param string $value
     */
    public function setLastEventId(string $value)
    {
        $this->flagManager->saveFlag(self::LAST_EVENT_ID_FLAG_NAME, $value);
    }

    /**
     * @param string $value
     */
    public function setLastUserId(string $value)
    {
        $this->flagManager->saveFlag(self::LAST_USER_ID_FLAG_NAME, $value);
    }
}
