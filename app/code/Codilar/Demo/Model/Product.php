<?php

namespace Codilar\Demo\Model;

class Product extends \Magento\Catalog\Model\Product {
    public function getName()
    {
        $name =  parent::getName();
        $price = $this->getData('price');
        if ($price < 60) {
            $name .= "So cheap";
        }else {
            $name .= "So bloody expensive";
        }

        return $name;
    }
}
