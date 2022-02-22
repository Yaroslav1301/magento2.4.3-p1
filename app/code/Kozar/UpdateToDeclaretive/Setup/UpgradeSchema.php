<?php

namespace Kozar\UpdateToDeclaretive\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            if ($installer->tableExists('quote_item_file')) {

                $installer->getConnection()->addColumn(
                    $installer->getTable('quote_item_file'),
                    'test_name',
                    [
                        'type' => Table::TYPE_TEXT,
                        255,
                        'nullable' => false,
                        'comment' => 'Test'
                    ]
                );

            }
        }
        $installer->endSetup();
    }
}
