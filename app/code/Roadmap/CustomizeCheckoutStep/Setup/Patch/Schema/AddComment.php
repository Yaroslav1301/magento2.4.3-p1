<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Roadmap\CustomizeCheckoutStep\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Roadmap\CustomizeCheckoutStep\Setup\SetupService\CommentFieldToQuoteAndOrderService;

/**
 * Patch is mechanism, that allows to do atomic upgrade data changes
 */
class AddComment implements
    SchemaPatchInterface
{
    /**
     * @var ModuleDataSetupInterface $moduleDataSetup
     */
    private $moduleDataSetup;

    /**
     * @var CommentFieldToQuoteAndOrderService
     */
    protected $commentFieldToQuoteAndOrderService;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CommentFieldToQuoteAndOrderService $commentFieldToQuoteAndOrderService
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CommentFieldToQuoteAndOrderService $commentFieldToQuoteAndOrderService
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->commentFieldToQuoteAndOrderService = $commentFieldToQuoteAndOrderService;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $this->commentFieldToQuoteAndOrderService->execute($this->moduleDataSetup);
        $this->moduleDataSetup->endSetup();
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

