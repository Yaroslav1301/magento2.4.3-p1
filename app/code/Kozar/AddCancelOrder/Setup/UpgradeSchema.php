<?php

namespace Kozar\AddCancelOrder\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.7', '<')) {
            if (!$installer->tableExists('canceled_orders_grid')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('canceled_orders_grid')
                )
                    ->addColumn(
                        'id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'ID'
                    )
                    ->addColumn(
                        'index',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [
                            'nullable' => false
                        ],
                        'Order Id'
                    )
                    ->addColumn(
                        'reason',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [
                            'nullable' => false,
                        ],
                        'Cancellation Reason',
                    )
                    ->addColumn(
                        'comment',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [
                            'nullable' => false,
                        ],
                        'Comment',
                    )
                    ->addColumn(
                        'action',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Action',
                    )
                    ->addColumn(
                        'cancled_by',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Canceled by',
                    )
                    ->setComment('Post Table');

                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('canceled_orders_grid'),
                    $setup->getIdxName(
                        $installer->getTable('canceled_orders_grid'),
                        ['id', 'index','reason', 'comment', 'action', 'cancled_by'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['id', 'index','reason', 'comment', 'action', 'cancled_by'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );

            }
        }
        $installer->endSetup();
    }
}
