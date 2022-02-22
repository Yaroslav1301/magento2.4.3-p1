<?php
namespace Kozar\Test\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $register;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->register = $registry;
        parent::__construct($context, $data);
    }

    public function getRegisterK() {
        return $this->register->registry("kozar");
    }
}
