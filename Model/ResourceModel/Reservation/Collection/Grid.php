<?php


namespace Macopedia\Allegro\Model\ResourceModel\Reservation\Collection;


use Macopedia\Allegro\Model\ResourceModel\Reservation;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Grid extends SearchResult
{
    /**
     * Grid constructor.
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     * @param null $identifierName
     * @param null $connectionName
     * @throws LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'allegro_reservations',
        $resourceModel = Reservation::class,
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $mainTable,
            $resourceModel,
            $identifierName,
            $connectionName
        );
    }

    /**
     * @return SearchResult+
     */
    protected function _beforeLoad()
    {
        $this->joinOriginalReservations();
        return parent::_beforeLoad();
    }

    protected function _initSelect()
    {
        $this
            ->addFilterToMap("reservation_id", "main_table.reservation_id")
            ->addFilterToMap("sku", "main_table.sku");

        return parent::_initSelect();
    }

    /**
     *
     */
    private function joinOriginalReservations()
    {
        $this->join(['org' => 'inventory_reservation'], 'main_table.reservation_id = org.reservation_id', '*');
    }
}
