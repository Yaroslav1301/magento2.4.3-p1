<?php

namespace Kozar\ProductImagesUploader\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if ($setup->tableExists('kozar_gallery')) {
            $tableName = $setup->getTable('kozar_gallery');
            $version = $context->getVersion();
            if ($version && version_compare($version, '1.0.1') < 0) {
                $setup->getConnection()->addIndex(
                    $tableName,
                    $setup->getIdxName(
                        'kozar_gallery',
                        ['product_id']
                    ),
                    ['product_id']
                );
            }
        }
        $setup->endSetup();
    }
}
