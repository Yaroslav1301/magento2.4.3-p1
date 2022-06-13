<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;
use Psr\Log\LoggerInterface;
use Roadmap\UiComponent\Model\Gift;
use Roadmap\UiComponent\Model\GiftFactory;

class Duplicate extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var GiftRepositoryInterface
     */
    protected $giftRepository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var GiftFactory
     */
    protected $giftFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param GiftRepositoryInterface $giftRepository
     * @param LoggerInterface $logger
     * @param GiftFactory $giftFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        GiftRepositoryInterface $giftRepository,
        LoggerInterface $logger,
        GiftFactory $giftFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->giftRepository = $giftRepository;
        $this->logger = $logger;
        $this->giftFactory = $giftFactory;
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

                /**
                 * @var Gift $newGift
                 */
                $newGift = $this->giftFactory->create();
                $newName = $gift->getName() . '-duplicate';
                $newGift->setName($newName);
                $newGift->setDescription($gift->getDescription());
                $newGift->setIsActive($newGift->isActive());
                $newGift->setMediaUrl($gift->getMediaUrl());
                $newGift->setRelatedProductSku($gift->getRelatedProductSku());
                $this->giftRepository->save($newGift);

                $this->messageManager->addSuccessMessage(
                    __(
                        'Gift %1 record(s) has been created.',
                        $newName
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
