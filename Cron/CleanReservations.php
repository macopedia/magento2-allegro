<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class CleanReservations
{
    const RESERVATIONS_CONFIG_KEY = 'allegro/order/reservations_enabled';

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var AllegroReservation */
    private $allegroReservation;

    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param AllegroReservation $allegroReservation
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        AllegroReservation $allegroReservation
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->allegroReservation = $allegroReservation;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::RESERVATIONS_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob clean reservations is executed.");
            $this->allegroReservation->cleanOldReservations();
        }
    }
}
