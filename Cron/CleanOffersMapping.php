<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OffersMapping;

class CleanOffersMapping
{
    const OFFERS_MAPPING_CRON_CONFIG_KEY = 'allegro/order/offers_mapping_cron_enabled';

    /** @var Logger */
    protected $logger;

    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var OffersMapping */
    protected $offersMapping;

    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param OffersMapping $offersMapping
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        OffersMapping $offersMapping
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->offersMapping = $offersMapping;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::OFFERS_MAPPING_CRON_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob clean offers mapping is executed.");
            try {
                $this->offersMapping->clean();
            } catch (\Exception $e) {
                $this->logger->error('Error while trying to clean old offers mapping: ' . $e->getMessage());
            }
        }
    }
}
