<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class responsible for importing orders from Allegro API
 */
class ImportOrders
{
    const ORDER_IMPORT_CONFIG_KEY = 'allegro/order/enabled';

    /** @var Logger */
    private $logger;

    /** @var OrderImporter */
    private $orderImporter;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @param Logger $logger
     * @param OrderImporter $orderImporter
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Logger $logger,
        OrderImporter $orderImporter,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->orderImporter = $orderImporter;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->scopeConfig->getValue(self::ORDER_IMPORT_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob imported orders is executed.");
            $this->orderImporter->execute();
        }
    }
}
