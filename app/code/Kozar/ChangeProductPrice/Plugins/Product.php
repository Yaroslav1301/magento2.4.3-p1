<?php

namespace Kozar\ChangeProductPrice\Plugins;

class Product {
    public function aftergetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $price = $subject->getData('price');
        $result = $price + 100;
        return $result;
    }
}
