<?php

namespace Kozar\AddCustomPriceAtr\Plugins;

class SaveButton
{
    protected $_productCollectionFactory;
    protected $_productRepository;
    protected $_helper;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\AddCustomPriceAtr\Helper\Data $_helper
    ) {
        $this->_productRepository = $productRepository;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helper = $_helper;
    }

    public function afteraddChild($alias, $block, $data = [])
    {
        $collection = $this->_productCollectionFactory->create();
        $custom_price_arr = $collection->addFieldToFilter('custom_price', ['neq' => 0]);
        $percent = (+($this->_helper->getGeneralConfig('display_text'))/100) + 1;
        foreach ($custom_price_arr as $value) {
            $id = $value->getId();
            $product = $this->_productRepository->getById($id);
            $base_price = +($base_price = $product->getData('price'));
            $allow_modify = +($product->getData('allow_modify'));
            if (!$allow_modify){
                $product->setCustomAttribute('custom_price', $base_price * $percent);
                $this->_productRepository->save($product);
            }

        }
    }
}
