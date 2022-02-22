<?php

namespace Dev101\UniversalWidget\Model;

class SortOrder implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
        ['value' => 'asc', 'label' => __('Ascending')],
        ['value' => 'desc', 'label' => __('Descending')]
    ];
    }
}
