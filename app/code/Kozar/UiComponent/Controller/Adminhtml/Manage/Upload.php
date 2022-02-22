<?php


namespace Kozar\UiComponent\Controller\Adminhtml\Manage;

use Magento\Backend\App\AbstractAction;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Upload
 * @package Kozar\UiComponent\Controller\Adminhtml\Manage
 */
class Upload extends AbstractAction
{
    /**
     * @var \Magento\Catalog\Model\ImageUploader
     */
    protected $imageUploader;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $fileSystem;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $fileIo;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Upload constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Model\ImageUploader $imageUploader
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Model\ImageUploader $imageUploader,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->imageUploader = $imageUploader;
        $this->fileSystem = $fileSystem;
        $this->fileIo = $fileIo;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    public function execute()
    {
        try {
            $imageUploadId = $this->getRequest()->getParam('param_name');
            $result = $this->imageUploader->saveFileToTmpDir($imageUploadId);

            $imageName = $result['name'];
            $firstName = substr($imageName, 0, 1);
            $secondName = substr($imageName, 1, 1);

            $basePath = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath() . 'logo/image/';
            $mediaRootDir = $this->fileSystem
                    ->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath() . 'logo/image/' . $firstName . '/' . $secondName . '/';
            if (!is_dir($mediaRootDir)) {
                $this->fileIo->mkdir($mediaRootDir, 0775);
            }
            $newImageName = $this->updateImageName($mediaRootDir, $imageName);
            $this->fileIo->mv($basePath . $imageName, $mediaRootDir . $newImageName);

            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $result['name'] = $newImageName;
            $result['file'] = $newImageName;
            $result['url'] = $mediaUrl . 'logo/image/' . $firstName . '/' . $secondName . '/' . $newImageName;
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    public function updateImageName($path, $fileName): string
    {
        if ($position = strrpos($fileName, '.')) {
            $name = substr($fileName, 0, $position);
            $extension = substr($fileName, $position);
        } else {
            $name = $fileName;
        }
        $newFilePath = $path . '/' . $fileName;
        $newFileName = $fileName;
        $count = 0;
        while (file_exists($newFilePath)) {
            $newFileName = $name . '_' . $count . $extension;
            $newFilePath = $path . '/' . $newFileName;
            $count++;
        }
        return $newFileName;
    }
}
