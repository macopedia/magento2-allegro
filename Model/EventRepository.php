<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\EventInterface;
use Macopedia\Allegro\Api\Data\EventInterfaceFactory;
use Macopedia\Allegro\Api\EventRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Order\Events;

class EventRepository implements EventRepositoryInterface
{

    /** @var Events */
    private $events;

    /** @var EventInterfaceFactory */
    private $eventFactory;

    /**
     * EventRepository constructor.
     * @param Events $events
     * @param EventInterfaceFactory $eventFactory
     */
    public function __construct(Events $events, EventInterfaceFactory $eventFactory)
    {
        $this->events = $events;
        $this->eventFactory = $eventFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        try {

            $eventsData = $this->events->getList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $events = [];
        foreach ($eventsData as $eventData) {
            /** @var EventInterface $event */
            $event = $this->eventFactory->create();
            $event->setRawData($eventData);
            $events[] = $event;
        }
        return $events;
    }

    /**
     * {@inheritDoc}
     */
    public function getListFrom(string $from): array
    {
        try {

            $eventsData = $this->events->getListFrom($from);

        } catch (ClientResponseException $e) {
            return [];
        }

        $events = [];
        foreach ($eventsData as $eventData) {
            /** @var EventInterface $event */
            $event = $this->eventFactory->create();
            $event->setRawData($eventData);
            $events[] = $event;
        }
        return $events;
    }
}
