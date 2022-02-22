<?php
namespace Kozar\Basic\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper
{
    protected $categoryFactory;
    protected $categoryCollectionFactory;
    protected $_view;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        Context $context
    ) {
        $this->_view = $view;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    const XML_PATH_BASIC = 'multi_select_section/';


    public function getConfigValue($field, $storeId = null)
    {
        $value =  $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        return $value;
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_BASIC . 'general/' . $code, $storeId);
    }

    public function isInCategory($id)
    {
        if ($this->_view->getProduct()) {
            $curent_product = $this->_view->getProduct();
            $canBeShown = $curent_product->canBeShowInCategory($id);
            if ($canBeShown) {
                return $curent_product;
            }
            return false;
        }
    }

    public function getRow()
    {
        $enable_module = $this->getGeneralConfig("enable");
        if ($enable_module) {
            $id = $this->getGeneralConfig("multiselect");

            if ($curent_product = $this->isInCategory($id)) {
                $sku = $curent_product->getSku();
                $type = $type = $curent_product->getTypeId();
                $collection = $this->categoryFactory->create()->load($id);
                $name = $collection->getName();
                return $name . "_" . $id . "_" . $sku . " " . $type;
            }
        }
        return "";
    }

    public function needToShowDiscountDate()
    {
        $enable_module = $this->getGeneralConfig("enable_additional");
        if ($enable_module) {
            $date_string = $this->getGeneralConfig("mydate");
            $date_my = strtotime($date_string);

            $date_now = time();

            $day_left = abs($date_my - $date_now) / 86400;

            $id = $this->getGeneralConfig("multiselect_additional");

            if ($current_product = $this->isInCategory($id)) {
                if ($day_left <= 10) {
                    return [true, $date_string];
                }
            }
            return [false, $date_string];
        }
        return [false, ""];
    }

    public function showDiscountPrice()
    {
        if ($enable_discount = $this->getGeneralConfig("discount_enable")) {
            if ($current_product = $this->_view->getProduct()) {
                $price = $current_product->getFinalPrice();
                $discount = (int)$this->getGeneralConfig("discount_text");
                if ($discount >= 100) {
                    $discount = 99;
                } elseif (empty($discount)) {
                    $discount = 10;
                }
                $price_with_discount = $price* ((100 - $discount)/100);
                return [$price_with_discount, $discount];
            }
        }
        return "";
    }

    public function isCronEnable()
    {
        $cron_value = $this->getGeneralConfig("cron_enable");
        if ($cron_value) {
            return true;
        }
        return false;

    }
}
