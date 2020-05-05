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
 * Puts message in a queue after product stock change
 */
class OrderObserver implements ObserverInterface
{
    const TOPIC_NAME = 'allegro.change.stock';
    const DB_TOPIC_NAME = 'allegro.change.stock.db';

    /** @var PublisherInterface  */
    protected $publisher;

    /** @var MessageInterfaceFactory  */
    protected $messageFactory;

    /** @var Logger */
    protected $logger;

    /** @var Configuration */
    private $config;

    /** @var Manager */
    protected $moduleManager;

    /**
     * OrderObserver constructor.
     * @param PublisherInterface $publisher
     * @param MessageInterface $message
     * @param Logger $logger
     * @param \Magento\Framework\Module\Manager $moduleManager
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
     * @return $this|void
     */
    public function execute(Observer $observer)
    {
        try {

            if (!$this->config->isStockSynchronizationEnabled()) {
                return;
            }

            $order = $observer->getEvent()->getData('order');
            if (!$order) {
                $this->logger->error('Error while getting data from event');
                return;
            }

            $items = $order->getItems();
            foreach ($items as $item) {

                /** @var MessageInterface $message */
                $message = $this->messageFactory->create();
                $message->setProductId($item->getProductId());

                if ($this->moduleManager->isEnabled('Magento_Amqp')) {
                    $this->publisher->publish(self::TOPIC_NAME, $message);
                    continue;
                }

                if ($this->moduleManager->isEnabled('Magento_MysqlMq')) {
                    $this->publisher->publish(self::DB_TOPIC_NAME, $message);
                    continue;
                }

            }

        } catch (\Exception $e) {
            $this->logger->exception($e);
        }
    }
}
