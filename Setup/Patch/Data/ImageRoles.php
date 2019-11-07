<?php

namespace Macopedia\Allegro\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Image role patch script
 */
class ImageRoles implements DataPatchInterface
{
    /** @var EavSetupFactory  */
    private $eavSetupFactory;

    /** @var ModuleDataSetupInterface  */
    private $moduleDataSetup;

    /**
     * ImageRoles constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return DataPatchInterface|void
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'allegro_image',
            [
                'type' => 'varchar',
                'label' => 'Allegro',
                'input' => 'media_image',
                'frontend' => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'filterable' => true,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'sort_order' => 10,
                'required' => false,
            ]
        );
    }
}
