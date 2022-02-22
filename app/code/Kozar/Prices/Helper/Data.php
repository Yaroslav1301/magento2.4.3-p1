<?php
namespace Kozar\Prices\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_HELLOWORLD = 'setPrices/';
    protected $_view;
    protected $_productCollectionFactory;
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        Context $context)
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_view = $view;
        parent::__construct($context);
    }


    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD .'general/'. $code, $storeId);
    }

    public function getModuleEnableArray() {
        $module_enable = $this->getGeneralConfig("enable");
        $base_price_enable = $this->getGeneralConfig("base_price");
        $final_price_enable = $this->getGeneralConfig("final_price");
        $special_price_enable = $this->getGeneralConfig("special_price");
        $tier_price_enable = $this->getGeneralConfig("tier_price");
        $catalog_price_enable = $this->getGeneralConfig("catalog_rule_price");

        return [$module_enable, $base_price_enable, $final_price_enable, $special_price_enable, $tier_price_enable, $catalog_price_enable];
    }
}
