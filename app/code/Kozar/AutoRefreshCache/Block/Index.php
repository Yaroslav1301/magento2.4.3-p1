<?php
namespace Kozar\AutoRefreshCache\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_productImageHelper;
    protected $_productRepository;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        parent::__construct($context, $data);
    }

}
