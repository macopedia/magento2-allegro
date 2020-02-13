<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Model\ResourceModel\Order\EventStats;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class LastEventIdInitializer
{
    /** @var EventStats */
    private $eventStats;

    /** @var Configuration */
    private $configuration;

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    /**
     * LastEventIdInitializer constructor.
     * @param EventStats $eventStats
     * @param Configuration $configuration
     * @param DateTimeFactory $dateTimeFactory
     */
    public function __construct(
        EventStats $eventStats,
        Configuration $configuration,
        DateTimeFactory $dateTimeFactory
    ) {
        $this->eventStats = $eventStats;
        $this->configuration = $configuration;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * @throws Api\ClientException
     * @throws Api\ClientResponseErrorException
     * @throws Api\ClientResponseException
     */
    public function initialize()
    {
        $lastUserId = $this->configuration->getLastUserId();
        $currentUserId = $this->eventStats->getCurrentUserId();
        $lastEventId = $this->configuration->getLastEventId();
        if ($lastEventId && $lastUserId === $currentUserId) {
            return;
        }
        $this->updateLastUserIdAndLastEventId($currentUserId);
    }

    /**
     * @param $currentUserId
     * @throws Api\ClientException
     * @throws Api\ClientResponseErrorException
     * @throws Api\ClientResponseException
     */
    private function updateLastUserIdAndLastEventId($currentUserId)
    {
        // TODO: Use EventRepositoryInterface
        $lastEvent = $this->eventStats->getLastEvent();
        if (!empty($lastEvent['id'])) {
            $lastEventId = $lastEvent['id'];
            $this->configuration->setLastEventId($lastEventId);
        }
        /** @var DateTime $dateTime */
        $dateTime = $this->dateTimeFactory->create();

        $this->configuration->setLastUserId($currentUserId);
        $this->configuration->setInitializationTime($dateTime->timestamp());
    }
}
