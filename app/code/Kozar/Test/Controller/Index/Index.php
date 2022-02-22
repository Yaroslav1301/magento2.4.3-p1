<?php
namespace Kozar\Test\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $registry;
    public function __construct(\Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $pageFactory,
    \Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    public function execute()
    {
        $this->registry->register('kozar',"kozar");
        return $this->_pageFactory->create();
    }
}
