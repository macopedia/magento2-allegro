<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Macopedia\Allegro\Model\Api\Credentials;

/**
 * Allegro credentials change observer
 */
class ConfigCredentialsChangeObserver implements ObserverInterface
{
    private static $credentialKeys = [
        'allegro/credentials/api_key',
        'allegro/credentials/client_id'
    ];

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var WriterInterface */
    private $configWriter;

    /** @var ManagerInterface */
    private $messageManager;

    /** @var Credentials  */
    private $credentials;

    /**
     * ConfigCredentialsChangeObserver constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param ManagerInterface $messageManager
     * @param Credentials $credentials
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        ManagerInterface $messageManager,
        Credentials $credentials
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->messageManager = $messageManager;
        $this->credentials = $credentials;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $changedPaths = $observer->getData('changed_paths');
        if (!empty(array_intersect($changedPaths, self::$credentialKeys))) {
            try {
                $this->credentials->getToken();
                $this->credentials->deleteToken();
                $this->messageManager->addNoticeMessage(__('You have changed credentials to Allegro account. Your current connection has been lost and you have to connect with Allegro account again'));
            } catch (ClientException $e) {
                $this->messageManager->addNoticeMessage(__('You have entered credentials to Allegro account. Now you have to click "Connect with Allegro account" button'));
            }
        }
    }
}
