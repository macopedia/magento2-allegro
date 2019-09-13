<?php

namespace Macopedia\Allegro\Model\System\Message;

use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Notification\MessageInterface;
use Macopedia\Allegro\Model\Api\Credentials;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Allegro connection status message class
 */
class AllegroConnectionStatusMessage implements MessageInterface
{

    const MESSAGE_IDENTITY = 'allegro_connection_status_message';
    const ORDER_IMPORT_CONFIG_KEY = 'allegro/order/enabled';

    /** @var Credentials  */
    private $credentials;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * CustomSystemMessage constructor.
     * @param Credentials $credentials
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Credentials $credentials,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->credentials = $credentials;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve unique system message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return self::MESSAGE_IDENTITY;
    }

    /**
     * Check whether the system message should be shown
     *
     * @return bool
     */
    public function isDisplayed()
    {
        if (!$this->scopeConfig->getValue(self::ORDER_IMPORT_CONFIG_KEY)) {
            return false;
        }

        try {
            $this->credentials->getToken();
            return true;
        } catch (ClientException $e) {
            return false;
        }
    }

    /**
     * Retrieve system message text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getText()
    {
        return __('Cannot connect with Allegro account');
    }

    /**
     * Retrieve system message severity
     * Possible default system message types:
     * - MessageInterface::SEVERITY_CRITICAL
     * - MessageInterface::SEVERITY_MAJOR
     * - MessageInterface::SEVERITY_MINOR
     * - MessageInterface::SEVERITY_NOTICE
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }
}
