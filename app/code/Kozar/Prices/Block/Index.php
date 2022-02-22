<?php
namespace Kozar\Prices\Block;

use Exception;
use Magento\Catalog\Api\SpecialPriceStorageInterface;
use Magento\Catalog\Api\TierPriceStorageInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_helperData;
    protected $_productRepository;
    protected $_view;
    protected $_rulePriceFactory;
    protected $_tierPrice;
    protected $_specialPrice;
    protected $_configurable;
    public function __construct(
        Configurable $configurable,
        TierPriceStorageInterface $tierPrice,
        SpecialPriceStorageInterface $specialPrice,
       // \Magento\Catalog\Pricing\Price\BasePrice $basePrice,
        \Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory $rule,
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\Prices\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        $this->_configurable = $configurable;
        $this->_specialPrice = $specialPrice;
        $this->_tierPrice = $tierPrice;
        $this->_rulePriceFactory = $rule;
        $this->_view = $view;
        $this->_productRepository = $productRepository;
        $this->_helperData = $helperData;
        parent::__construct($context, $data);
    }

    public function testVersion()
    {
        $curent_product = $this->_view->getProduct();
        $sku = $curent_product->getSku();
        $id = $curent_product->getId();
        $show_for_config_sku_field = false;
        if ($curent_product->getTypeId() == "bundle") {
            return ["-", "-", "-", "-", "-"];
        } else {
            if ($curent_product->canConfigure()) {
                $base_price = $curent_product->getPriceInfo()->getPrice('regular_price');
                $regularPrice = $base_price->getMinRegularAmount()->getValue();
                $base_price = $base_price->getValue();

                $arr_ids = $this->_configurable->getChildrenIds($id)[0];
                $temp_value = 9999;
                foreach ($arr_ids as $value) {
                    $congif_sku = $this->_productRepository->getById($value)->getSku();
                    $check_in = $this->_specialPrice->get([$congif_sku]);
                    if (!empty($check_in)) {
                        $special_price = (float)$check_in[0]->getPrice();
                        if ($special_price < $temp_value && $special_price != null) {
                            $temp_value = $special_price;
                            $name_of_special_price_sku = $congif_sku;
                            $show_for_config_sku_field = true;
                        }
                    }
                }
                if (!empty($special_price)) {
                    $special_price = $temp_value;
                } else {
                    $special_price = "-";
                }
            } else {
                //$regularPrice = $curent_product->getPriceInfo()->getPrice('regular_price')->getValue();
                $base_price = ($curent_product->getPrice()) ? $curent_product->getPrice() : "-";
                $special_price = ($curent_product->getSpecialPrice()) ? $curent_product->getSpecialPrice() : "-";
            }

            /*
            * getting tier price
            */

            if ($tier_price_discount = $this->_tierPrice->get([$sku])) {
                $tier_price_discount = $this->_tierPrice->get([$sku])[0]->getData('price');
                $tier_price =  $base_price * ((100 - $tier_price_discount)/100);
            } else {
                $tier_price = "-";
            }

            $final_price = ($curent_product->getFinalPrice()) ? $curent_product->getFinalPrice() : "-";
            /*
            * getting catalog rule price like doing it magento regardless of minimum final price
            */
            $catalogRule = null;
            try {
                $ruleName = 'kozarRule';
                $catalogRule = $this->_rulePriceFactory->create()
                    ->addFieldToFilter('name', $ruleName)->getData();
                $is_active = $catalogRule[0]['is_active'];
                $discount_amount = (float)$catalogRule[0]['discount_amount'];
                if ($is_active) {
                    $catalog_rule_price = $base_price * ((100 - $discount_amount)/100);
                }
            } catch (Exception $exception) {
                $catalog_rule_price = "-";
            }
            if ($show_for_config_sku_field) {
                $min_value_after_special = 9999;
                $arr = [$base_price, $tier_price, $catalog_rule_price];
                foreach ($arr as $value) {
                    if ($value != "-" && $min_value_after_special > $value) {
                        $min_value_after_special = $value;
                    }
                }
                return [$base_price, $final_price, $special_price, $tier_price, $catalog_rule_price, $name_of_special_price_sku, $min_value_after_special];
            } else {
                return [$base_price, $final_price, $special_price, $tier_price, $catalog_rule_price];
            }
        }
    }

    public function getAllows()
    {
        return $this->_helperData->getModuleEnableArray();
    }
}
