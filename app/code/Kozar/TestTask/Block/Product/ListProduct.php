<?php
namespace Kozar\TestTask\Block\Product;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Url\Helper\Data;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_helperData;
    public function __construct(\Kozar\TestTask\Helper\Data $helperData, Context $context, PostHelper $postDataHelper, Resolver $layerResolver, CategoryRepositoryInterface $categoryRepository, Data $urlHelper, array $data = [])
    {
        $this->_helperData = $helperData;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    public function getProductDetailsHtml(\Magento\Catalog\Model\Product $product)
    {
        $html = $this->getLayout()->createBlock('Kozar\TestTask\Block\Index')->setProduct($product)->setTemplate('Kozar_TestTask::customTemplate.phtml')->toHtml();
        $renderer = $this->getDetailsRenderer($product->getTypeId());
        if ($renderer) {
            $renderer->setProduct($product);
            return $html.$renderer->toHtml();
        }
        return '';
    }

}
