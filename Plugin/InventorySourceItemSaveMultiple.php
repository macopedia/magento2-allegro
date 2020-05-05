<?php

namespace Macopedia\Allegro\Plugin;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Service\MessageQtyChange;
use Magento\Framework\App\ResourceConnection;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

/**
 * Puts message in a queue after save multiple action
 */
class InventorySourceItemSaveMultiple
{
    /**
     * @var MessageQtyChange
     */
    protected $messageQtyChange;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param MessageQtyChange $messageQtyChange
     * @param Logger $logger
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        MessageQtyChange $messageQtyChange,
        Logger $logger,
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->messageQtyChange   = $messageQtyChange;
        $this->logger             = $logger;
    }

    /**
     * @param $subject
     * @param callable $proceed
     * @param array $sourceItems
     * @return void
     */
    public function aroundExecute($subject, callable $proceed, array $sourceItems)
    {
        if (empty($sourceItems)) {
            return;
        }

        $proceed($sourceItems);

        try {
            $skus = array_map(function (SourceItemInterface $i) {
                return $i->getSku();
            }, $sourceItems);

            $connection = $this->resourceConnection->getConnection();
            $tableName  = $this->resourceConnection->getTableName('catalog_product_entity');

            $select = $connection->select()
                ->from($tableName, 'entity_id')
                ->where('sku IN (?)', $skus);

            $result = $connection->fetchCol($select);

            foreach ($result as $productId) {
                $this->messageQtyChange->execute($productId);
            }
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }
}
