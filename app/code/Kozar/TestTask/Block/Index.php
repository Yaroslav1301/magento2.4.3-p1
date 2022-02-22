<?php
namespace Kozar\TestTask\Block;
use Kozar\TestTask\Helper\Data;
use phpseclib\Math\BigInteger;


class Index extends \Magento\Framework\View\Element\Template {


    protected $_helperData;
    protected $_productRepository;
    protected $_session;
    protected $_view;

    public function __construct(
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\TestTask\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->_view = $view;
        $this->_productRepository = $productRepository;
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }


    public function getInformation($value, $course) {

        if ($this->getProduct()) {
            $price = (float)$this->getProduct()->getFinalPrice();
        }else {
            $price = (float)$this->_view->getProduct()->getFinalPrice();
        }

        $enable = $this->_helperData->getGeneralConfig($value);
        $course_final = $this->_helperData->getGeneralConfig($course);
        $course_final = str_replace(',', '.', $course_final);
        $course_final = (float)$course_final;
        $course_final *= $price;
        $course_final = ceil($course_final);
        return [$enable, $course_final];
    }

}
