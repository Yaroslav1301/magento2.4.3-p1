<?php

declare(strict_types=1);

namespace Kozar\UiComponent\Controller\Adminhtml\Manage;

use Magento\Backend\App\AbstractAction;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use StudyPool\ServiceContract\Api\StudyPoolScRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Edit
 *
 * @package StudyPool\UiComponent\Controller\Adminhtml\Manage
 */
class Edit extends AbstractAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Kozar_UiComponent::ui_app';

    /**
     * @var StudyPoolScRepositoryInterface
     */
    private $repository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param StudyPoolScRepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        StudyPoolScRepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Study Poll Ui edit page
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($entityId) {
            try {
                $studyPoolSc = $this->repository->getById($entityId);
                $resultPage->setActiveMenu(self::ADMIN_RESOURCE);
                $resultPage->getConfig()->getTitle()
                    ->prepend(__('Edit Configure Ui Configuration'));
            } catch (\Throwable $throwable) {
                $this->logger->critical($throwable->getMessage());
                $this->messageManager->addErrorMessage(__(
                    'An error occured. Please, try again later.'
                ));
                return $resultRedirect->setPath('*/*/index');
            }
            $resultPage->getConfig()->getTitle()
                ->prepend(__('Edit row for "%1"', $studyPoolSc->getName()));
            return $resultPage;
        } else {
            $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Add new row in DB'));
        }
        return $resultPage;
    }
}
