<?php


namespace Kozar\ProductImagesUploader\Block\Adminhtml\Product\Helper\Form\Gallary;

use Magento\Backend\Block\Media\Uploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;


/**
 * Kozar gallery content
 */
class Content  extends \Magento\Backend\Block\Widget {
    /**
     * @var string
     */
    protected $_template = "Kozar_ProductImagesUploader::product/edit/kozar/gallery.phtml";

    /**
     * @var \Kozar\ProductImagesUploader\Model\Product\Media\Config
     */
    protected $_mediaConfig;
    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $_jsonEncoder;

    private $_imageHelper;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Serialize\SerializerInterface $jsonEncoder,
        \Kozar\ProductImagesUploader\Model\Product\Media\Config $mediaConfig,
        \Magento\Catalog\Helper\Image $imageHelper,
        array $data = []
    )
    {
        $this->_jsonEncoder = $jsonEncoder;
        $this->_mediaConfig = $mediaConfig;
        $this->_imageHelper = $imageHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\View\Element\AbstractBlock
     */
    protected function _prepareLayout()
    {
        $this->addChild('uploader', 'Magento\Backend\Block\Media\Uploader', ['template' => 'Kozar_ProductImagesUploader::media/uploader.phtml']); //TODO create phtml

        $mageVersion = '2.3.4'; //TODO for remove this

        if (version_compare($mageVersion, '2.3.5', '<')) {
            $url = $this->_urlBuilder->addSessionParam()->getUrl('kozar/gallery/upload');
        }else {
            $url = $this->_urlBuilder->getUrl('kozar/gallery/upload');
        }

        $this->getUploader()->getConfig()->setUrl(
            $url
        )->setFileField(
            'image'
        )->setFilters(
            [
                'images' => [
                    'label' => __('Images (.gif, .jpg, .png)'),
                    'files' => ['*.gif', '*.jpg', '*.jpeg', '*.png'],
                ],
            ]
        );
        return parent::_prepareLayout();
    }

    public function getUploader()
    {
        return $this->getChildBlock('uploader');
    }

    public function getUploaderHtml()
    {
        return $this->getChildHtml('uploader');
    }

    public function getJsObjectName()
    {
        return $this->getHtmlId() . 'JsObject';
    }

    public function getImagesJson()
    {
        $imagesValue = $this->getElement()->getImages();
        if (is_array($imagesValue) && count($imagesValue)) {
            $directory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
         foreach ($imagesValue as &$image) {
             $image['url'] = $this->_mediaConfig->getMediaUrl($image['file']);
             try {
                 $fileHandler = $directory->stat($this->_mediaConfig->getMediaPath($image['file']));
                 $image['size'] = $fileHandler['size'];
             }catch (FileSystemException $e) {
                 $image['url'] = $this->getImageHelper()->getDefaultPlaceholderUrl('small_image');
                 $image['size'] = 0;
                 $this->_logger->warning($e);
             }
         }
         return $this->_jsonEncoder->serialize($imagesValue);
        }


    }

    private function getImageHelper()
    {
        return $this->_imageHelper;
    }

}
