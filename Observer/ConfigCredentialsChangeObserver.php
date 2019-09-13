<?php

namespace Macopedia\Allegro\Observer;

use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;

/**
 * Allegro credentials change observer
 */
class ConfigCredentialsChangeObserver implements ObserverInterface
{
    const TOKEN_DATA_CONFIG_KEY = 'allegro/credentials/token_data';

    private static $credentialKeys = [
        'allegro/credentials/api_key',
        'allegro/credentials/client_id',
        'allegro/credentials/token_data'
    ];

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var WriterInterface */
    private $configWriter;

    /** @var ManagerInterface */
    private $messageManager;

    /** @var TypeListInterface */
    private $cacheTypeList;

    /**
     * ConfigCredentialsChangeObserver constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $configWriter
     * @param ManagerInterface $messageManager
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        ManagerInterface $messageManager,
        TypeListInterface $cacheTypeList
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->messageManager = $messageManager;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $changedPaths = $observer->getData('changed_paths');
        if (!empty(array_intersect($changedPaths, self::$credentialKeys))) {
            if ($this->scopeConfig->getValue(self::TOKEN_DATA_CONFIG_KEY)) {
                $this->configWriter->delete(self::TOKEN_DATA_CONFIG_KEY);
                $this->messageManager->addNoticeMessage(__('You have changed credentials to Allegro account. Your current connection has been lost and you have to connect with Allegro account again'));
            }
            $this->cacheTypeList->cleanType('config');
        }
    }
}
