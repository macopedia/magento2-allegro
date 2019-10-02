<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Model\ResourceModel\Order\EventStats;

class LastEventIdInitializer
{
    /** @var EventStats */
    protected $eventStats;

    /** @var Configuration */
    protected $configuration;

    /**
     * LastEventIdInitializer constructor.
     * @param EventStats $eventStats
     * @param Configuration $configuration
     */
    public function __construct(
        EventStats $eventStats,
        Configuration $configuration
    ) {
        $this->eventStats = $eventStats;
        $this->configuration = $configuration;
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
        if ($lastUserId) {
            if ($lastUserId !== $currentUserId) {
                $this->updateLastUserIdAndLastEventId($currentUserId);
            }
        } else {
            $this->updateLastUserIdAndLastEventId($currentUserId);
        }
    }

    /**
     * @param $currentUserId
     * @throws Api\ClientException
     * @throws Api\ClientResponseErrorException
     * @throws Api\ClientResponseException
     */
    private function updateLastUserIdAndLastEventId($currentUserId)
    {
        $lastEvent = $this->eventStats->getLastEvent();
        $lastEventId = $lastEvent['id'];
        $this->configuration->setLastEventId($lastEventId);
        $this->configuration->setLastUserId($currentUserId);
    }
}
