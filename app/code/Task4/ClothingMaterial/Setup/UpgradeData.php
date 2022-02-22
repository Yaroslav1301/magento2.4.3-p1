<?php

namespace Task4\ClothingMaterial\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;


class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;
    /**
     * Init
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup'=>$setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'attribute_id',
                [
                    'type' => 'int',
                    'label' => 'KozarCategory',
                    'input' => 'boolean',
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'visible' => true,
                    'default' => '0',
                    'required' => false,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'group' => 'Display Settings',
                ]
            );
        }



    }
}
