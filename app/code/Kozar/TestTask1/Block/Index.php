<?php

namespace Kozar\TestTask1\Block;

use Kozar\TestTask1\Helper\Data;
use phpseclib\Math\BigInteger;


class Index extends \Magento\Framework\View\Element\Template
{


    protected $_helperData;
    protected $_productRepository;
    protected $_session;
    protected $_view;
    protected $_stockItemRepository;
    protected $configurable;

    public function __construct(
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurable,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\TestTask1\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->configurable = $configurable;
        $this->_stockItemRepository = $stockItemRepository;
        $this->_view = $view;
        $this->_productRepository = $productRepository;
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }


    public function getInformation($name_kozar, $enable)
    {

        if ($this->getProduct()) {
            $id = $this->getProduct()->getId();
            $arr_Ids = $this->configurable->getChildrenIds($id);
            $value = $arr_Ids[0];
            foreach ($value as $key => $item) {
                $id = (int)$item;
                break;
            }
            $qnty = $this->_stockItemRepository->get($id)->getQty();

        } else {
            $id = $this->_view->getProduct()->getId();
            $arr_Ids = $this->configurable->getChildrenIds($id);

            $value = $arr_Ids[0];
            foreach ($value as $item) {
                $id = (int)$item;
                break;
            }
            $qnty = $this->_stockItemRepository->get($id)->getQty();
        }


        $value_X = $this->_helperData->getGeneralConfig($name_kozar);
        $enable_module = $this->_helperData->getGeneralConfig($enable);
        return [$value_X, $qnty, $enable_module];
    }

}
