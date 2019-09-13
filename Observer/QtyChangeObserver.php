<?php

namespace Macopedia\Allegro\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Macopedia\Allegro\Api\Consumer\MessageInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Product stock change observer
 */
class QtyChangeObserver implements ObserverInterface
{
    const ORDER_IMPORT_CONFIG_KEY = 'allegro/order/enabled';

    const DB_TOPIC_NAME = 'allegro.change.stock.db';
    const TOPIC_NAME    = 'allegro.change.stock';

    /** @var PublisherInterface  */
    protected $publisher;

    /** @var MessageInterface  */
    protected $message;

    /** @var Logger */
    protected $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * OrderObserver constructor.
     * @param PublisherInterface $publisher
     * @param MessageInterface $message
     * @param Logger $logger
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        PublisherInterface $publisher,
        MessageInterface $message,
        Logger $logger,
        \Magento\Framework\Module\Manager $moduleManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->publisher = $publisher;
        $this->message = $message;
        $this->logger = $logger;
        $this->moduleManager = $moduleManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            if (!$this->scopeConfig->getValue(self::ORDER_IMPORT_CONFIG_KEY)) {
                return;
            }

            $productId = $observer->getEvent()->getData('item')->getProductId();

            if (!$productId) {
                $this->logger->error('Error while getting data from event');
                return;
            }

            $this->message->setProductId($productId);

            if ($this->moduleManager->isEnabled('Magento_Amqp')) {
                $this->publisher->publish(self::TOPIC_NAME, $this->message);
            } elseif ($this->moduleManager->isEnabled('Magento_MysqlMq')) {
                $this->publisher->publish(self::DB_TOPIC_NAME, $this->message);
            }

        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
