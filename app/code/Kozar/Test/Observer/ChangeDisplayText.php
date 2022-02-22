<?php

namespace Kozar\Test\Observer;

use Magento\Framework\Event\Observer;

class ChangeDisplayText implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(Observer $observer)
    {
        $product = $observer->getProduct();
        $originalName = $product->getName();
        $modifiedName = $originalName . '- Content added by Kozar';
        $product->setName($modifiedName);
    }
}
