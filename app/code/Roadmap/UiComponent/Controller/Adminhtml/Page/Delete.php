<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;
use Psr\Log\LoggerInterface;

class Delete extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var GiftRepositoryInterface
     */
    private $giftRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param GiftRepositoryInterface $giftRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        GiftRepositoryInterface $giftRepository,
        LoggerInterface $logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->giftRepository = $giftRepository;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $giftId = (int)$this->getRequest()->getParam('gift_id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            if ($giftId) {
                $gift = $this->giftRepository->getById($giftId);
                $this->giftRepository->deleteById($gift->getGiftId());

                $this->messageManager->addSuccessMessage(
                    __(
                        'Gift %1 record(s) has been deleted.',
                        $gift->getName()
                    )
                );
            }
        } catch (\Throwable $throwable) {
            $this->logger->critical($throwable->getMessage());
            $this->messageManager->addErrorMessage(__(
                'An error occurred. Please, try again later.'
            ));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
