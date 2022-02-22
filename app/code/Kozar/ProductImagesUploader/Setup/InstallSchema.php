<?php

namespace Kozar\ProductImagesUploader\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Install Schema Script
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * Create table kozar_gallery
         */

        if (!$setup->tableExists('kozar_gallery')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('kozar_gallery')
            )->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true,
                ],
                'ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false,
                    'default' => '0'
                ],
                'Product ID'
            )->addColumn(
                'position',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true
                ],
                'Position'
            )->addColumn(
                'file',
                Table::TYPE_TEXT,
                255,
                [],
                'File'
            )->addIndex(
                $setup->getIdxName(
                    'kozar_gallery',
                    ['product_id']
                ),
                ['product_id']
            )->setComment('Kozar gallery');
            $setup->getConnection()->createTable($table);
        }
    }
}
