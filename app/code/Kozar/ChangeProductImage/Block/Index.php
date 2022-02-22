<?php
namespace Kozar\ChangeProductImage\Block;
class Index extends \Magento\Framework\View\Element\Template
{
    protected $_productImageHelper;
    protected $_productRepository;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Helper\Image $productImage,
        array $data = []
    )
    {
        $this->_productRepository = $productRepository;
        $this->_productImageHelper = $productImage;
        parent::__construct($context, $data);
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    public function getProductBySku($sku)
    {
        return $this->_productRepository->get($sku);
    }


    public function resizeImage($product, $imageId, $width, $height = null)
    {
        $resizedImage = $this->_productImageHelper->init($product, $imageId)
            ->constrainOnly(TRUE)
            ->keepAspectRatio(TRUE)
            ->keepFrame(FALSE)
            ->resize($width, $height);

        return $resizedImage;
    }
}
