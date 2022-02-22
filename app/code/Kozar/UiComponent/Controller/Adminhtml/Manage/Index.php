<?php


namespace Kozar\UiComponent\Controller\Adminhtml\Manage;

use Magento\Backend\App\AbstractAction;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Index
 * @package Kozar\UiComponent\Controller\Adminhtml\Index
 */
class Index extends AbstractAction implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Kozar_UiComponent::ui_app';

    /**
     * UiComponent classes list action
     *
     * @return void
     */
    public function execute(): void
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu(self::ADMIN_RESOURCE);
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Ui Component Study'));
        $this->_view->renderLayout();
    }
}
