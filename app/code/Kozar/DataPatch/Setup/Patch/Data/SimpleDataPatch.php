<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kozar\DataPatch\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes
 */
class SimpleDataPatch implements
    DataPatchInterface,
    PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->logger = $logger;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $dataForDeclarative [] = ['severity' => 1, 'title' => 'Test Title', 'time_occurred'=> null, 'foreign_key' => 1];
        $dataForForeignTable [] = ['id_column' => 3, 'severity' => 1, 'title' => 'Test Title'];
        $this->moduleDataSetup->getConnection()->insertArray(
            $this->moduleDataSetup->getTable('table_for_foreign_key'),
            ['id_column', 'severity', 'title'],
            $dataForForeignTable
        );
        $this->moduleDataSetup->getConnection()->insertArray(
            $this->moduleDataSetup->getTable('declarative_table'),
            ['severity', 'title', 'time_occurred', 'foreign_key'],
            $dataForDeclarative
        );

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function revert()
    {
        $this->logger->info('In Simple Data Schema Patch');
        $this->logger->info('This function is working when module is uninstalling
        by this command bin/magento module:uninstall --non-composer Kozar_DataPatch');
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
