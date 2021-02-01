<?php


namespace Macopedia\Allegro\Setup\Patch\Data;


use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class OrderStatuses implements DataPatchInterface
{
    const ALLEGRO_UNDERPAYMENT_STATUS = 'allegro_underpayment';
    const ALLEGRO_OVERPAYMENT_STATUS = 'allegro_overpayment';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * OrderStatuses constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup) {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return OrderStatuses|void
     */
    public function apply()
    {
        $connection = $this->moduleDataSetup->getConnection();

        $connection->startSetup();

        $sosTable = $this->moduleDataSetup->getTable('sales_order_status');
        $sossTable = $this->moduleDataSetup->getTable('sales_order_status_state');

        //Remove incorrect statuses
        $connection->delete($sossTable, ['state = ?' => self::ALLEGRO_UNDERPAYMENT_STATUS]);
        $connection->delete($sossTable, ['state = ?' => self::ALLEGRO_OVERPAYMENT_STATUS]);

        $data[] = ['status' => self::ALLEGRO_UNDERPAYMENT_STATUS, 'label' => 'Allegro underpayment'];
        $data[] = ['status' => self::ALLEGRO_OVERPAYMENT_STATUS, 'label' => 'Allegro overpayment'];
        $connection->insertArray(
            $sosTable,
            ['status', 'label'],
            $data
        );

        $connection->insertArray(
            $sossTable,
            ['status', 'state', 'is_default','visible_on_front'],
            [
                [self::ALLEGRO_UNDERPAYMENT_STATUS,'new', '0', '1'],
                [self::ALLEGRO_OVERPAYMENT_STATUS, 'new', '0', '1']
            ]
        );

        $connection->endSetup();
    }
}
