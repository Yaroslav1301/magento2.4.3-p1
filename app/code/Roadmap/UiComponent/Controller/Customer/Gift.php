<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Controller\Customer;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;

class Gift implements HttpGetActionInterface
{
    private $customerSession;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Session $customerSession
     * @param PageFactory $pageFactory
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param ManagerInterface $messageManager
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        Session $customerSession,
        PageFactory $pageFactory,
        RequestInterface $request,
        ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        UrlInterface $urlBuilder
    ) {
        $this->customerSession = $customerSession;
        $this->pageFactory = $pageFactory;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->pageFactory->create();
        }

        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result->setPath('customer/account/login');
        $this->messageManager->addErrorMessage(__('You should be logged in to visit that page'));
        $this->customerSession->setBeforeAuthUrl($this->urlBuilder->getUrl(
            'suggest/customer/gift'
        ));
        return  $result;
    }
}
