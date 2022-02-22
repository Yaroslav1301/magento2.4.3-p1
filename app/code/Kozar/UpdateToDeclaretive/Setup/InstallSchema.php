<?php

namespace Kozar\UpdateToDeclaretive\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Create table 'quote_item_file'
         */
        $table = $setup->getConnection()
            ->newTable($setup->getTable('quote_item_file'))
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'primary' => true, 'identity' => true],
                'Entity ID'
            )
            ->addColumn(
                'filename',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Filename'
            )->addColumn(
                'location',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Location of file'
            )->addColumn(
                'quote_item_item_id',
                Table::TYPE_INTEGER,
                null,
                ['padding' => 10, 'nullable' => false, 'unsigned' => true],
                'Item Id'
            )->addForeignKey(
                $setup->getFkName(
                    $setup->getTable('quote_item_file'),
                    'quote_item_item_id',
                    'quote_item',
                    'item_id'
                ),
                'quote_item_item_id',
                $setup->getTable('quote_item'),
                'item_id',
                Table::ACTION_CASCADE
            )->setComment('Quote Item File Table');

        $setup->getConnection()->createTable($table);
    }
}
