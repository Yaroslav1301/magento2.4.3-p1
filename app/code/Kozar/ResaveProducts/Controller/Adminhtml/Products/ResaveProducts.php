<?php


namespace Kozar\ResaveProducts\Controller\Adminhtml\Products;


use Magento\Backend\App\Action;
use Kozar\ResaveProducts\Logger\Logger;

class ResaveProducts extends \Magento\Backend\App\Action
{
    protected $_productCollectionFactory;

    protected $_logger;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        Logger $_logger,
        Action\Context $context)
    {
        $this->_productCollectionFactory = $collectionFactory;
        $this->_logger = $_logger;

        parent::__construct($context);
    }

    public function execute()
    {
        $time_start = microtime(true);

        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $items = $collection->getItems();

        foreach ($items as $item) {
            $sku = $item->getSku();
            try {
                $item->save();
            }catch (\Exception $e) {
                $this->_logger->info('Product SKU: ' . $sku);
                $this->_logger->critical($e->getMessage());
            }
        }

        $time_end = microtime(true);

        $time = $time_end - $time_start;

        $this->_logger->info('Execution time is : ' . $time . " seconds");
    }
}
