<?php

namespace Kozar\Basic\Plugins;

class Product {
    protected $data;
    public function __construct(\Kozar\Basic\Helper\Data $data)
    {
        $this->data = $data;
    }
    public function aftergetName(\Magento\Catalog\Model\Product $product, $name) {
        $added_row = (string)$this->data->getRow();
        $name .= " ".$added_row ;
        return $name;
    }
}
