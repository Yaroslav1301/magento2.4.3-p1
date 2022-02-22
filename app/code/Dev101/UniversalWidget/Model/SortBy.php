<?php


namespace Dev101\UniversalWidget\Model;


class SortBy implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'id', 'label' => __('Product ID')],
            ['value' => 'name', 'label' => __('Name')],
            ['value' => 'price', 'label' => __('Price')]
        ];
    }
}



