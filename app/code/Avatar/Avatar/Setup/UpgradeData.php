<?php

namespace Avatar\Avatar\Setup;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class Upgrade Data
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    protected $customerSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * Cunstructor
     * @param CustomerSetupFactory
     * @param AttributeSetFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory  = $attributeSetFactory;
    }

    /**
     * Adding Custom Attribute to Magento
     * @param ModuleDataSetupInterface
     * @param ModuleContextInterface
     * @return null
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig()->getEntityType('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId();

        $attributeSet     = $this->attributeSetFactory->create();
        $attributeGroupId = $attributeSet->getDefaultGroupId($attributeSetId);
        $customerSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'profile_picture',
            [
                'group'        => 'General',
                'type'         => 'text',
                'label'        => 'Profile Picture',
                'input'        => 'image',
                'required'     => false,
                'visible'      => true,
                'is_html_allowed_on_front' => true,
                'visible_on_front' => true,
                'user_defined' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => true,
                'sort_order'   => 1000,
                'position'     => 1000,
                'system'       => 0,
            ]
        );
        $Attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'profile_picture')
            ->addData([
                'attribute_set_id'   => 1,
                'attribute_group_id' => 1,
                'used_in_forms'      => ['adminhtml_customer', 'checkout_register', 'customer_account_create', 'customer_account_edit', 'adminhtml_checkout'],
            ]);

        $Attribute->save();
        $setup->endSetup();
    }
}