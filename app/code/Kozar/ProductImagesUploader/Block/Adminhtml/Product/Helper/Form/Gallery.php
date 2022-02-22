<?php

namespace Kozar\ProductImagesUploader\Block\Adminhtml\Product\Helper\Form;


class Gallery  extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * Gallery html id
     * @var string
     */
    protected $htmlId = 'kozar_gallery';

    /**
     * Gallery name
     * @var string
     */
    protected $name = 'kozar[gallery]';

    /**
     * Form name
     * @var string
     */
    protected $formName = 'product_form';

    protected $registry;

    protected $modelGalleryFactory = null;

    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Framework\Registry $registry,
        \Kozar\ProductImagesUploader\Model\GalleryFactory $modelGalleryFactory,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->modelGalleryFactory = $modelGalleryFactory;
        parent::__construct($context, $data);
    }


    public function getElementHtml()
    {
        return $this->getContentHtml();
    }

    public function getImages()
    {
        $productId = $this->registry->registry('current_product')->getId();
        $galleryModel = $this->modelGalleryFactory->create();
        $galleryCollection = $galleryModel->getCollection();
        $galleryCollection->addFieldToFilter('product_id', $productId);
        $galleryCollection->setOrder('position', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        return $galleryCollection->getData() ?: null;
    }

    public function getContentHtml()
    {
        /**
         *  @var $content \Kozar\ProductImagesUploader\Block\Adminhtml\Product\Helper\Form\Gallary\Content
         */
        $content = $this->getChildBlock('kozar_content');
        $content->setId($this->getHtmlId() . '_content');
        $content->setElement($this);
        $content->setFormName($this->formName); // TODO
        $galleryJs = $content->getJsObjectName();
        $content->getUploader()->getConfig()->setMegiaGallery($galleryJs);
        return $content->toHtml();
    }


    protected function getHtmlId()
    {
        return $this->htmlId;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getElementHtml();
    }


}
