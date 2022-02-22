<?php

namespace Kozar\SimpleViewModel\ViewModel;

class Simple implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    public function getSomeText()
    {
        return "I am your data from ViewModel";
    }
}
