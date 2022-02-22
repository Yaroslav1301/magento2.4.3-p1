<?php
namespace Kozar\HelloWorld\Block;

class AddProductBlock extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    public function showProduct()
    {
        return "Added new block under the Name";
    }
}
