<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kozar\CustomerModification\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\ResourceModel\Attribute;
use Magento\Customer\Api\CustomerMetadataInterface;

class AddAttributeForCustomer implements
    DataPatchInterface,
    PatchRevertableInterface
{
    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var Attribute
     */
    private $attributeResourceModel;

    /**
     * @param Attribute $attributeResourceModel
     * @param Config $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        Attribute $attributeResourceModel,
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->attributeResourceModel = $attributeResourceModel;
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /**
         *  @var \Magento\Eav\Setup\EavSetup $eavSetup
         */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'test_customer_attribute',
            [
                'label'        => 'Test Customer Attribute',
                'input'        => 'text',
                'required'     => 0,
                'visible'      => 1,
                'user_defined' => 1,
                'position'     => 999,
                'system'       => 0,
            ]
        );

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            'test_customer_attribute'
        );

        $sampleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'test_customer_attribute');

        $sampleAttribute->setData(
            'used_in_forms',
            [
                'adminhtml_checkout',
                'adminhtml_customer',
                'adminhtml_customer_address',
                'customer_account_edit',
                'customer_address_edit',
                'customer_register_address',
                'customer_account'
            ]
        );
        $this->attributeResourceModel->save($sampleAttribute);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->removeAttribute(Customer::ENTITY, 'test_customer_attribute');
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [

        ];
    }
}
