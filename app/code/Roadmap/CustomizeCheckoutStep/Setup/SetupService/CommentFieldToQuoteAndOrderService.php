<?php

namespace  Roadmap\CustomizeCheckoutStep\Setup\SetupService;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Quote\Setup\QuoteSetup;
use Magento\Sales\Setup\SalesSetup;
use Roadmap\CustomizeCheckoutStep\Setup\SchemaInformation;

class CommentFieldToQuoteAndOrderService
{
    /**
     * @var \Magento\Quote\Setup\QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @param \Magento\Quote\Setup\QuoteSetupFactory $quoteSetupFactory
     * @param \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        \Magento\Quote\Setup\QuoteSetupFactory $quoteSetupFactory,
        \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $dataSetup
     * @return void
     */
    public function execute(ModuleDataSetupInterface $dataSetup)
    {
        $this->addAttributeToQuote(
            SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT,
            SchemaInformation::ATTRIBUTE_ATTR,
            $dataSetup
        );

        $this->addAttributeToOrder(
            SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT,
            SchemaInformation::ATTRIBUTE_ATTR,
            $dataSetup
        );

        $this->addAttributeToOrderGrid(
            SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT,
            SchemaInformation::ATTRIBUTE_ATTR,
            $dataSetup
        );
    }

    /**
     *  Add attribute to quote
     * @param $attributeCode
     * @param $attributeAttr
     * @param $dataSetup
     * @return void
     */
    protected function addAttributeToQuote($attributeCode, $attributeAttr, $dataSetup)
    {
        /** @var QuoteSetup $quoteSetup */
        $quoteSetup = $this->quoteSetupFactory->create(
            [
                'setup' => $dataSetup,
            ]
        );
        $quoteSetup->addAttribute('quote', $attributeCode, $attributeAttr);
    }

    /**
     * Add attribute to order
     * @param $attributeCode
     * @param $attributeAttr
     * @param $dataSetup
     * @return void
     */
    protected function addAttributeToOrder($attributeCode, $attributeAttr, $dataSetup)
    {
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(
            [
                'setup' => $dataSetup,
            ]
        );
        $salesSetup->addAttribute('order', $attributeCode, $attributeAttr);
    }

    /**
     *  Add attribute to order grid
     * @param $attributeCode
     * @param $attributeAttr
     * @param $dataSetup
     * @return void
     */
    protected function addAttributeToOrderGrid($attributeCode, $attributeAttr, $dataSetup)
    {
        $dataSetup->getConnection()->addColumn(
            $dataSetup->getTable('sales_order_grid'),
            $attributeCode,
            $attributeAttr
        );
    }
}

