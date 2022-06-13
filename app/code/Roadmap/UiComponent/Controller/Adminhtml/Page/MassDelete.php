<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Ui\Component\MassAction\Filter;
use Roadmap\UiComponent\Model\ResourceModel\Gift\CollectionFactory;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class MassDelete extends Action
{
    public const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var CollectionFactory
     */
    private $giftCollectionFactory;

    /**
     * @var GiftRepositoryInterface
     */
    private $giftRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $logger
     * @param Filter $filter
     * @param CollectionFactory $giftCollectionFactory
     * @param GiftRepositoryInterface $giftRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        LoggerInterface $logger,
        Filter $filter,
        CollectionFactory $giftCollectionFactory,
        GiftRepositoryInterface $giftRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
        $this->filter = $filter;
        $this->giftCollectionFactory = $giftCollectionFactory;
        $this->giftRepository = $giftRepository;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $collection = $this->filter->getCollection($this->giftCollectionFactory->create());

            $deleted = 0;
            foreach ($collection as $item) {
                $this->giftRepository->deleteById((int)$item['gift_id']);
                $deleted++;
            }
            if ($deleted) {
                $this->messageManager->addSuccessMessage(
                    __(
                        'A total gift of %1 record(s) has been deleted.',
                        $deleted
                    )
                );
            }
        } catch (NoSuchEntityException|
        CouldNotDeleteException|
        LocalizedException $exception) {
            $this->logger->error($exception->getMessage());
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
