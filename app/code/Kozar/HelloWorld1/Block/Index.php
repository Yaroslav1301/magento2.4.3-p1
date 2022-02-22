<?php
namespace Kozar\HelloWorld1\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_helperData;

    public function __construct(
        \Kozar\HelloWorld1\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }
    public function getMymodule()
    {
        $enable_value =  $this->_helperData->getGeneralConfig('enable');
        $display_text_value =  $this->_helperData->getGeneralConfig('display_text');

        return [$enable_value, $display_text_value];
    }
}
