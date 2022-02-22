<?php
namespace Kozar\HelloWorld\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    public function getMymodule()
    {
        return $this->_storeManager->getDefaultStoreView()->getName();
    }
}
