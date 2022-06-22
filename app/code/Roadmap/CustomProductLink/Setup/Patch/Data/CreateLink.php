<?php

declare(strict_types=1);

namespace Roadmap\CustomProductLink\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Roadmap\CustomProductLink\Model\Product\Link;

class CreateLink implements
    DataPatchInterface,
    PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Add new link type in DB
     *
     * @return void
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;

        $data = [
            [
                'link_type_id' => Link::LINK_TYPE_CUSTOMLINK,
                'code' => Link::LINK_TYPE
            ],
        ];

        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('catalog_product_link_type'), $bind);
        }
        $data = [
            [
                'link_type_id' => Link::LINK_TYPE_CUSTOMLINK,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int',
            ]
        ];
        $setup->getConnection()
            ->insertMultiple($setup->getTable('catalog_product_link_attribute'), $data);
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $setup = $this->moduleDataSetup;

        $where = ['link_type_id = ?' => Link::LINK_TYPE_CUSTOMLINK];
        $setup->getConnection()->delete(
            $setup->getTable('catalog_product_link_type'),
            $where
        );

        $setup->getConnection()->delete(
            $setup->getTable('catalog_product_link_attribute'),
            $where
        );
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
