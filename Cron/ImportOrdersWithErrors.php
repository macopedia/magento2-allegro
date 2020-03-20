<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderWithErrorImporter;

/**
 * Class responsible for importing orders with errors from Allegro API
 */
class ImportOrdersWithErrors
{
    const ORDER_IMPORT_CONFIG_KEY = 'allegro/order/enabled';

    /** @var Logger */
    private $logger;

    /** @var OrderWithErrorImporter */
    private $orderImporter;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * @param Logger $logger
     * @param OrderWithErrorImporter $orderImporter
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Logger $logger,
        OrderWithErrorImporter $orderImporter,
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
            $this->logger->addInfo("Cronjob imported orders with errors is executed.");
            $this->orderImporter->execute();
        }
    }
}
