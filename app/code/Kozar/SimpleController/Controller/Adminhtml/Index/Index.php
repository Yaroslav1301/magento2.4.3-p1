<?php

namespace Kozar\SimpleController\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Index extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    public function __construct(
        PageFactory $pageFactory,
        Context $context
    ) {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('Kozar_SimpleController::greetings_helloworld');
        $resultPage->getConfig()->getTitle()->prepend(__('Test Controller'));

        return $resultPage;
    }
}
