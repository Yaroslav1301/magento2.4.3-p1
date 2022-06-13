<?php

namespace Roadmap\UiComponent\Controller\Adminhtml\Page;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Roadmap\UiComponent\Model\ImageUploader;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Upload extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Roadmap_UiComponent::product_gifts_show';

    /**
     * @var ImageUploader
     */
    protected $imageUploader;

    /**
     * @param ImageUploader $imageUploader
     * @param Context $context
     */
    public function __construct(
        ImageUploader $imageUploader,
        Context $context
    ) {
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (LocalizedException $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
