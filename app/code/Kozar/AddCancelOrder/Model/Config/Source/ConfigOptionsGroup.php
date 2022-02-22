<?php

namespace Kozar\AddCancelOrder\Model\Config\Source;

class ConfigOptionsGroup implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'General', 'label' => __('General')],
            ['value' => 'Retailer', 'label' => __('Retailer')],
            ['value' => 'Wholesale', 'label' => __('Wholesale')],
        ];
    }
}
