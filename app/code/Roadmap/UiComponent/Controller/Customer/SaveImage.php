<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Controller\Customer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Roadmap\UiComponent\Model\ImageUploader;

class SaveImage implements HttpPostActionInterface
{
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var ImageUploader
     */
    private $imageFileUploader;

    /**
     * @param ResultFactory $resultFactory
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        ResultFactory $resultFactory,
        ImageUploader $imageUploader
    ) {
        $this->resultFactory = $resultFactory;
        $this->imageFileUploader = $imageUploader;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $result = $this->imageFileUploader->saveFileToTmpDir('suggest-image-uploader');
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
