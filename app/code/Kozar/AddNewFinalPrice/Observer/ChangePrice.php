<?php

namespace Kozar\AddNewFinalPrice\Observer;

use Magento\Framework\Event\Observer;

class ChangePrice implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $price = $product->getData('final_price');
        $new_price = $price * ((100 - 10)/100);
        $product->setFinalPrice($new_price);
    }
}
