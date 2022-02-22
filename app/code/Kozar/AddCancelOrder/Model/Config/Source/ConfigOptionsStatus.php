<?php

namespace Kozar\AddCancelOrder\Model\Config\Source;

class ConfigOptionsStatus implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'Pending', 'label' => __('Pending')],
            ['value' => 'Processing', 'label' => __('Processing')],
            ['value' => 'Suspected Fraud', 'label' => __('Suspected Fraud')],
            ['value' => 'Checking', 'label' => __('Checking')]
        ];
    }
}
