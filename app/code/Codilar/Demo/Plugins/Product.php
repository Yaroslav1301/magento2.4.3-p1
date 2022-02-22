<?php
namespace Codilar\Demo\Plugins;

class Product {

    public function aftergetName(\Magento\Catalog\Model\Product $product, $name) {
        $price = $product->getData('price');
        if ($price < 60) {
            $name .= "So cheap";
        }else {
            $name .= "So bloody expensive";
        }

        return $name;
    }
}
