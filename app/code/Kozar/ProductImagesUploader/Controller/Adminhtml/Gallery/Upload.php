<?php

namespace Kozar\ProductImagesUploader\Controller\Adminhtml\Gallery;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Upload extends \Magento\Backend\App\Action
{

    protected $resultRawFactory;

    protected $mediaConfig;

    protected $uploader;

    protected $adapter;

    protected $fileSystem;

    public function __construct(
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Kozar\ProductImagesUploader\Model\Product\Media\Config $mediaConfig,
        \Kozar\ProductImagesUploader\Model\File\Uploader $uploader,
        \Magento\Framework\Image\AdapterFactory $adapter,
        \Magento\Framework\Filesystem $fileSystem,
        Action\Context $context
    )
    {
        $this->resultRawFactory = $resultRawFactory;
        $this->mediaConfig = $mediaConfig;
        $this->uploader = $uploader;
        $this->adapter = $adapter;
        $this->fileSystem = $fileSystem;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $uploader  = $this->uploader;
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

            $imageAdapter = $this->adapter->create();
            $uploader->addValidateCallback('kozar_image', $imageAdapter, 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $mediaDirectory = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA);
            $result = $uploader->save($mediaDirectory->getAbsolutePath($this->mediaConfig->getBaseTmpMediaPath()));

            unset($result['tmp_name']);
            unset($result['path']);

            $result['url'] = $this->mediaConfig->getTmpMediaUrl($result['file']);
        }catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        $responce = $this->resultRawFactory->create();
        $responce->setHeader('Content-type', 'text/plain');
        $responce->setContents(json_encode($result));
        return $responce;
    }
}
