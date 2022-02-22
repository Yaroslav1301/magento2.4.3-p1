<?php

declare(strict_types=1);

namespace Kozar\UiComponent\Controller\Adminhtml\Manage;

use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use StudyPool\ServiceContract\Api\StudyPoolScRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Delete
 *
 * @package StudyPool\UiComponent\Controller\Adminhtml\Manage
 */
class Delete extends AbstractAction implements HttpGetActionInterface
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
     * Delete constructor.
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
     * Show deleting Study Poll Ui data
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $entityId = (int) $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            if ($entityId) {
                $studyPoolSc = $this->repository->getById($entityId);
                $this->repository->deleteById($studyPoolSc->getEntityId());

                $this->messageManager->addSuccessMessage(__(
                    'Study Poll Ui configuration "%1" has been deleted.',
                    $studyPoolSc->getName()
                ));
            }
        } catch (\Throwable $throwable) {
            $this->logger->critical($throwable->getMessage());
            $this->messageManager->addErrorMessage(__(
                'An error occured. Please, try again later.'
            ));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}
