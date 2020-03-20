<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Preference\Model\ResourceModel;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\ResourceConnection;
use Magento\InventoryReservationsApi\Model\ReservationInterface;
use Magento\InventoryReservations\Model\ResourceModel\SaveMultiple as MagentoSaveMultiple;
use Macopedia\Allegro\Api\ReservationRepositoryInterface;
use Macopedia\Allegro\Api\Data\ReservationInterfaceFactory;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Implementation of Reservation save multiple operation for specific db layer
 * Save Multiple used here for performance efficient purposes over single save operation
 */
class SaveMultiple extends MagentoSaveMultiple
{
    const ALLEGRO_EVENT_RESERVATION_PLACED = 'allegro_reservation_placed';
    const ALLEGRO_EVENT_RESERVATION_COMPENSATED = 'allegro_reservation_compensated';

    /** @var ResourceConnection */
    private $resourceConnection;

    /** @var ReservationRepositoryInterface */
    private $reservationRepository;

    /** @var ReservationInterfaceFactory */
    private $reservationInterfaceFactory;

    /** @var SerializerInterface */
    private $serializer;

    /** @var Logger */
    private $logger;

    /**
     * SaveMultiple constructor.
     * @param ResourceConnection $resourceConnection
     * @param ReservationRepositoryInterface $reservationRepository
     * @param ReservationInterfaceFactory $reservationInterfaceFactory
     * @param SerializerInterface $serializer
     * @param Logger $logger
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        ReservationRepositoryInterface $reservationRepository,
        ReservationInterfaceFactory $reservationInterfaceFactory,
        SerializerInterface $serializer,
        Logger $logger
    ) {
        parent::__construct($resourceConnection);
        $this->resourceConnection = $resourceConnection;
        $this->reservationRepository = $reservationRepository;
        $this->reservationInterfaceFactory = $reservationInterfaceFactory;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param array $reservations
     */
    public function execute(array $reservations)
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('inventory_reservation');

        $connection->beginTransaction();

        try {
            foreach ($reservations as $reservation) {
                $data = [
                    ReservationInterface::STOCK_ID => $reservation->getStockId(),
                    ReservationInterface::SKU => $reservation->getSku(),
                    ReservationInterface::QUANTITY => $reservation->getQuantity(),
                    ReservationInterface::METADATA => $reservation->getMetadata()
                ];
                $connection->insert($tableName, $data);
                $metadata = $this->serializer->unserialize($reservation->getMetadata());
                if ($metadata['event_type'] === self::ALLEGRO_EVENT_RESERVATION_PLACED) {
                    $reservationId = (int)$connection->lastInsertId();
                    $allegroReservation = $this->reservationInterfaceFactory->create();
                    $allegroReservation->setReservationId($reservationId);
                    $allegroReservation->setCheckoutFormId($metadata['object_id']);
                    $allegroReservation->setSku($reservation->getSku());
                    $this->reservationRepository->save($allegroReservation);
                } else {
                    if ($metadata['event_type'] === self::ALLEGRO_EVENT_RESERVATION_COMPENSATED) {
                        $this->reservationRepository
                            ->deleteBySkuAndCheckoutFormId($reservation->getSku(), $metadata['object_id']);
                    }
                }
            }
            $connection->commit();
        } catch (\Exception $e) {
            $this->logger->exception($e);
            $connection->rollBack();
        }
    }
}
