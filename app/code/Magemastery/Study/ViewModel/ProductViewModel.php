<?php
use Magento\Framework\View\Element\Block\ArgumentInterface;

class ProductViewModel implements ArgumentInterface
{
    private $resource;

    /*    public function __construct(Resource $resource)
        {
            $this->resource = $resource;
        }
    
        public function getProductBySku(string $sku): ProductInterface {
            return $this->resource->load($sku, 'sku');
        }*/
}
