<?php


namespace Kozar\ResaveProducts\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Kozar\ResaveProducts\Logger\Logger;

class Resave extends Command
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * Resave constructor.
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param Logger $_logger
     * @param \Magento\Framework\App\State $state
     * @param string|null $name
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        Logger $_logger,
        string $name = null
    )
    {
        $this->_productCollectionFactory = $collectionFactory;
        $this->_logger = $_logger;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("products:resave");
        $this->setDescription("Resave all products data");
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
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
        $output->writeln("Products resaved successfully. For error log see ../var/log/resaved_products.txt");
    }


}
