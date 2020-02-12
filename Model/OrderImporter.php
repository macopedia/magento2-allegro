<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\Data\EventInterface;
use Macopedia\Allegro\Api\EventRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Info;
use Macopedia\Allegro\Model\OrderImporter\Processor;

/**
 * Class responsible for handling events fetched from Allegro API
 */
class OrderImporter extends AbstractOrderImporter
{
    /** @var EventRepositoryInterface */
    private $eventRepository;

    /** @var Configuration */
    private $configuration;

    /**
     * OrderImporter constructor.
     * @param Logger $logger
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param Info $info
     * @param Configuration $configuration
     * @param EventRepositoryInterface $eventRepository
     */
    public function __construct(
        Logger $logger,
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        Info $info,
        Configuration $configuration,
        EventRepositoryInterface $eventRepository
    ) {
        parent::__construct($logger, $processor, $checkoutFormRepository, $info);
        $this->configuration = $configuration;
        $this->eventRepository = $eventRepository;
    }

    protected $processedCheckoutFormIds = [];

    /**
     * @return Info
     */
    public function execute() : Info
    {
        $this->processedCheckoutFormIds = [];
        while ($events = $this->fetchEvents()) {
            /** @var CheckoutFormInterface $checkoutForm */
            foreach ($events as $event) {
                $this->processEvent($event);
            }
        }
        return $this->prepareInfoResponse();
    }

    /**
     * @param EventInterface $event
     */
    protected function processEvent(EventInterface $event)
    {
        $checkoutFormId = $event->getCheckoutFormId();

        if (!in_array($checkoutFormId, $this->processedCheckoutFormIds)) {
            $this->processedCheckoutFormIds[] = $checkoutFormId;
            $this->tryToProcessOrder($checkoutFormId);
        }

        $this->configuration->setLastEventId($event->getId());
    }

    /**
     * @return array|EventInterface[]
     */
    protected function fetchEvents()
    {
        try {
            return $this->loadEvents();
        } catch (\Exception $e) {
            $this->logger->error('Wrong response received while fetching events data');
            return [];
        }
    }

    /**
     * @return array|EventInterface[]
     * @throws Api\ClientException
     */
    public function loadEvents()
    {
        $lastEventId = $this->configuration->getLastEventId();
        if ($lastEventId) {
            $events = $this->eventRepository->getListFrom($lastEventId);
        } else {
            $events = $this->eventRepository->getList();
        }
        return $events;
    }
}
