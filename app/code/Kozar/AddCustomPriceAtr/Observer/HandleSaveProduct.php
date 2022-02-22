<?php

namespace Kozar\AddCustomPriceAtr\Observer;

use Magento\Framework\Event\Observer;

class HandleSaveProduct implements \Magento\Framework\Event\ObserverInterface
{
    protected $request;
    protected $_productRepository;
    protected $_helper;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\AddCustomPriceAtr\Helper\Data $_helper
    ) {
        $this->request = $request;
        $this->_productRepository = $productRepository;
        $this->_helper = $_helper;
    }

    public function execute(Observer $observer)
    {
        if ($this->request->getParam('magenest') != null) {
            $params = $this->request->getParam('magenest');

            if ($productId = $this->request->getParam('id')) {
                $product = $observer->getData('product');
                $enable =  +($params['status']);

                if ($enable) {
                    $price = $params['textField'];
                    $product->setCustomAttribute('custom_price', $price);
                } else {
                    $percent = (+($this->_helper->getGeneralConfig('display_text'))/100)+1;
                    $price = +($product->getData('price')) * $percent;
                    $product->setCustomAttribute('custom_price', $price);
                }
                $product->setCustomAttribute('allow_modify', $enable);
            }
        }

    }
}
