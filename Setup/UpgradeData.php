<?php

namespace Macopedia\Allegro\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Sales\Model\ResourceModel\Order\Status;

/**
 * Upgrade data script
 */
class UpgradeData implements UpgradeDataInterface
{
    const OVERPAYMENT_STATE_CODE = 'allegro_overpayment';
    const UNDERPAYMENT_STATE_CODE = 'allegro_underpayment';
    const PENDING_STATUS = 'pending';

    /** @var Status */
    private $statusResource;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /** @var ResourceConnection */
    private $resource;

    /**
     * @param Status $statusResource
     * @param EavSetupFactory $eavSetupFactory
     * @param ResourceConnection $resource
     */
    public function __construct(
        Status $statusResource,
        EavSetupFactory $eavSetupFactory,
        ResourceConnection $resource
    ) {
        $this->statusResource = $statusResource;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->resource = $resource;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
            foreach ([self::OVERPAYMENT_STATE_CODE, self::UNDERPAYMENT_STATE_CODE] as $state) {
                $this->statusResource->assignState(self::PENDING_STATUS, $state, false);
            }

            $attribute = 'allegro_offer_id';
            $eavSetup = $this->createEavSetup($setup);
            $entityTypeId = $eavSetup->getEntityTypeId('catalog_product');
            $groupName = 'Allegro';
            $eavSetup->addAttribute($entityTypeId, $attribute, [
                'label' => __('Allegro offer ID'),
                'user_defined' => 1,
                'searchable' => 0,
                'visible_on_front' => 0,
                'required' => false,
                'visible_in_advanced_search' => 0,
                'group' => $groupName
            ]);
            foreach ($eavSetup->getAllAttributeSetIds($entityTypeId) as $attributeSetId) {
                $eavSetup->addAttributeGroup($entityTypeId, $attributeSetId, $groupName, 10);
                $attributeGroupId = $eavSetup->getAttributeGroupId($entityTypeId, $attributeSetId, $groupName);
                $attributeId = $eavSetup->getAttributeId($entityTypeId, $attribute);
                $eavSetup->addAttributeToGroup($entityTypeId, $attributeSetId, $attributeGroupId, $attributeId, null);
            }
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @return EavSetup
     */
    private function createEavSetup(ModuleDataSetupInterface $setup)
    {
        return $this->eavSetupFactory->create(['setup' => $setup]);
    }
}
