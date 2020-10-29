<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\FlagManager;

class Configuration
{
    const STOCK_SYNCHRONIZATION_ENABLED_CONFIG_PATH = 'allegro/order/stock_synchronization_enabled';
    const TRACKING_NUMBER_SENDING_ENABLED_CONFIG_PATH = 'allegro/order/tracking_number_sending_enabled';
    const DEBUG_MODE_ENABLED_CONFIG_PATH = 'allegro/debug_mode/debug_mode_enabled';
    const EAN_ATTRIBUTE_CONFIG_PATH = 'allegro/offer_create/ean_attribute';
    const DESCRIPTION_ATTRIBUTE_CONFIG_PATH = 'allegro/offer_create/description_attribute';
    const PRICE_ATTRIBUTE_CONFIG_PATH = 'allegro/offer_create/price_attribute';
    const STORE_ID_CONFIG_PATH = 'allegro/order/store';
    const RESERVATIONS_ENABLED_CONFIG_PATH = 'allegro/order/reservations_enabled';
    const RESERVATIONS_CRON_ENABLED_CONFIG_PATH = 'allegro/order/reservations_cron_enabled';
    const PRICE_POLICY_ENABLED_CONFIG_PATH = 'allegro/price_policy/price_policy_enabled';
    const PERCENT_INCREASE_CONFIG_PATH = 'allegro/price_policy/percent_increase';
    const LAST_EVENT_ID_FLAG_NAME = 'allegro_order_last_event_id';
    const LAST_USER_ID_FLAG_NAME = 'allegro_credentials_last_user_id';
    const INITIALIZATION_TIME_FLAG_NAME = 'allegro_initialization_time';

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
    public function isStockSynchronizationEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::STOCK_SYNCHRONIZATION_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isTrackingNumberSendingEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::TRACKING_NUMBER_SENDING_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isDebugModeEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::DEBUG_MODE_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function areReservationsEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::RESERVATIONS_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isReservationsCronEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::RESERVATIONS_CRON_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return bool
     */
    public function isPricePolicyEnabled(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): bool {
        return $this->scopeConfig->isSetFlag(self::PRICE_POLICY_ENABLED_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return string|null
     */
    public function getPricePercentIncrease(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): ?string {
        return $this->scopeConfig->getValue(self::PERCENT_INCREASE_CONFIG_PATH, $scopeType, $scopeCode);
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
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return string|null
     */
    public function getEanAttributeCode(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): ?string {
        return $this->scopeConfig->getValue(self::EAN_ATTRIBUTE_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return string|null
     */
    public function getDescriptionAttributeCode(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): ?string {
        return $this->scopeConfig->getValue(self::DESCRIPTION_ATTRIBUTE_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return string|null
     */
    public function getPriceAttributeCode(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ): ?string {
        return $this->scopeConfig->getValue(self::PRICE_ATTRIBUTE_CONFIG_PATH, $scopeType, $scopeCode);
    }

    /**
     * @return int
     */
    public function getInitializationTime(): int
    {
        return (int)$this->flagManager->getFlagData(self::INITIALIZATION_TIME_FLAG_NAME);
    }

    /**
     * @return int
     */
    public function getStoreId(): int
    {
        return (int)$this->scopeConfig->getValue(self::STORE_ID_CONFIG_PATH);
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

    /**
     * @param $time
     */
    public function setInitializationTime($time)
    {
        $this->flagManager->saveFlag(self::INITIALIZATION_TIME_FLAG_NAME, $time);
    }
}
