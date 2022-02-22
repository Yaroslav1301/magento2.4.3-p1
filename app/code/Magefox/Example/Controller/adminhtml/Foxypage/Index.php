<?php
namespace Magefox\Example\Controller\adminhtml\Foxypage;

class Index extends \Magento\Backend\App\Action {
    /**
     *
     * Check if user has enough privileges
     *
     * @retrun bool
     *
     */

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magefox_Example::foxypage');
    }

    /**
     * @retrun void
     */

    public function execute()
    {
        $this->_view->loadLayout();

        $this->_setActiveMenu('Magefox_Example::foxypage');
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Foxy Admin Page'));
        $this->_addBreadcrumb(__('Stores'), __('Stores'));
        $this->_addBreadcrumb(__('Foxy Admin Page'), __('Foxy Admin Page'));

        $this->_view->renderLayout();
    }
}
