<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Create extends Action implements HttpGetActionInterface
{
    const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_FORWARD)->forward('edit');
    }
}
