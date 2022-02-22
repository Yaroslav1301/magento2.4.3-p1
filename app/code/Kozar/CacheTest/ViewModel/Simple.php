<?php

namespace Kozar\CacheTest\ViewModel;

class Simple implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function getSomeText()
    {
        return "Simple HTML result from ViewModel";
    }
}
