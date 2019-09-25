<?php

namespace Macopedia\Allegro\Observer;

use Macopedia\Allegro\Model\Configuration;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Macopedia\Allegro\Api\Consumer\MessageInterface;
use Macopedia\Allegro\Api\Consumer\MessageInterfaceFactory;
use Magento\Framework\MessageQueue\PublisherInterface;
use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\Module\Manager;

/**
 * Product stock change observer
 */
class QtyChangeObserver implements ObserverInterface
{
    const DB_TOPIC_NAME = 'allegro.change.stock.db';
    const TOPIC_NAME    = 'allegro.change.stock';

    /** @var PublisherInterface  */
    private $publisher;

    /** @var MessageInterfaceFactory  */
    private $messageFactory;

    /** @var Logger */
    private $logger;

    /** @var Configuration */
    private $config;

    /** @var Manager */
    private $moduleManager;

    /**
     * QtyChangeObserver constructor.
     * @param PublisherInterface $publisher
     * @param MessageInterfaceFactory $messageFactory
     * @param Logger $logger
     * @param Manager $moduleManager
     * @param Configuration $config
     */
    public function __construct(
        PublisherInterface $publisher,
        MessageInterfaceFactory $messageFactory,
        Logger $logger,
        Manager $moduleManager,
        Configuration $config
    ) {
        $this->publisher = $publisher;
        $this->messageFactory = $messageFactory;
        $this->logger = $logger;
        $this->moduleManager = $moduleManager;
        $this->config = $config;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {

            if (!$this->config->isStockSynchronizationEnabled()) {
                return;
            }

            $productId = $observer->getEvent()->getData('item')->getProductId();

            if (!$productId) {
                $this->logger->error('Error while getting data from event');
                return;
            }

            /** @var MessageInterface $message */
            $message = $this->messageFactory->create();
            $message->setProductId($productId);

            if ($this->moduleManager->isEnabled('Magento_Amqp')) {
                $this->publisher->publish(self::TOPIC_NAME, $message);
                return;
            }

            if ($this->moduleManager->isEnabled('Magento_MysqlMq')) {
                $this->publisher->publish(self::DB_TOPIC_NAME, $message);
                return;
            }

        } catch (\Exception $e) {
            $this->logger->exception($e);
        }
    }
}
