<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;

class Edit extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var GiftRepositoryInterface
     */
    private $giftRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param GiftRepositoryInterface $giftRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        GiftRepositoryInterface $giftRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->giftRepository = $giftRepository;
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute()
    {
        $giftId = (int) $this->getRequest()->getParam('gift_id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($giftId) {
            try {
                $gift = $this->giftRepository->getById($giftId);
                $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
                $resultPage->getConfig()->getTitle()
                    ->prepend(__('Edit Configure Ui Configuration'));
            } catch (\Throwable $throwable) {
                $this->messageManager->addErrorMessage(__(
                    'An error occurred. Please, try again later.'
                ));
                return $resultRedirect->setPath('*/*/index');
            }
            $resultPage->getConfig()->getTitle()
                ->prepend(__('Edit row for "%1"', $gift->getName()));
            return $resultPage;
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Add New Gift'));
        }
        return $resultPage;
    }
}
