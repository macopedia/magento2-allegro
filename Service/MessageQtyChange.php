<?php

namespace Macopedia\Allegro\Service;

use Macopedia\Allegro\Api\Consumer\MessageInterface;
use Macopedia\Allegro\Api\Consumer\MessageInterfaceFactory;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Configuration;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Module\Manager;

/**
 * Product stock change observer
 */
class MessageQtyChange
{
    const DB_TOPIC_NAME = 'allegro.change.stock.db';
    const TOPIC_NAME    = 'allegro.change.stock';

    /** @var PublisherInterface */
    private $publisher;

    /** @var MessageInterfaceFactory */
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
        $this->publisher      = $publisher;
        $this->messageFactory = $messageFactory;
        $this->logger         = $logger;
        $this->moduleManager  = $moduleManager;
        $this->config         = $config;
    }

    /**
     * @param int $productId
     * @return void
     */
    public function execute($productId)
    {
        try {
            if (!$productId || !$this->config->isStockSynchronizationEnabled()) {
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
