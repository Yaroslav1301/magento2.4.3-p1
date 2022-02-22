<?php

namespace Kozar\SimpleAdminPanel\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

class Review extends \Magento\Backend\App\Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Kozar_SimpleAdminPanel::view';

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
        return $this->pageFactory->create();
    }
}
