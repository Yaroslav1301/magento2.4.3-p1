<?php


namespace Kozar\UiComponent\Controller\Adminhtml\Manage;

use Magento\Backend\Model\View\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Create
 * @package Kozar\UiComponent\Controller\Adminhtml\Index
 */
class Create extends AbstractAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Kozar_UiComponent::ui_app';

    /**
     * Forward to edit
     *
     * @return Forward
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('edit');
    }
}
