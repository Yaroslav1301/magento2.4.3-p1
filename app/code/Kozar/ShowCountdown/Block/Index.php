<?php
namespace Kozar\ShowCountdown\Block;

use Magento\Catalog\Api\SpecialPriceStorageInterface;
use Magento\CatalogRule\Model\ResourceModel\Rule\CollectionFactory as RuleCollectionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Http\Context as AuthContext;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $ruleCollectionFactory;
    protected $_helperData;
    protected $_productRepository;
    protected $_session;
    protected $_view;
    protected $_configurable;
    protected $_specialPrice;
    protected $customerGroupCollection;
    protected $authContext;
    protected $rule;
    public function __construct(
        \Magento\CatalogRule\Model\ResourceModel\Rule $rule,
        AuthContext $authContext,
        \Magento\Customer\Model\ResourceModel\Group\Collection $customerGroupCollection,
        RuleCollectionFactory $ruleCollectionFactory,
        \Magento\Catalog\Block\Product\View\AbstractView $view,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Kozar\TestTask\Helper\Data $helperData,
        \Magento\Backend\Block\Template\Context $context,
        Configurable $configurable,
        SpecialPriceStorageInterface $specialPrice,
        array $data = []
    ) {
        $this->rule =$rule;
        $this->authContext = $authContext;
        $this->ruleCollectionFactory = $ruleCollectionFactory;
        $this->_configurable = $configurable;
        $this->_view = $view;
        $this->_productRepository = $productRepository;
        $this->_helperData = $helperData;
        $this->_specialPrice = $specialPrice;
        $this->customerGroupCollection = $customerGroupCollection;
        parent::__construct($context, $data);
    }

    public function getSpecialPriceInformation()
    {
        $curent_product = $this->_view->getProduct();
        $id = $curent_product->getId();

        if ($curent_product->canConfigure()) {
            $arr_ids = $this->_configurable->getChildrenIds($id)[0];
            $temp_value = 9999;
            foreach ($arr_ids as $value) {
                $congif_sku = $this->_productRepository->getById($value)->getSku();
                $check_in = $this->_specialPrice->get([$congif_sku]);
                if (!empty($check_in)) {
                    $special_price = (float)$check_in[0]->getPrice();
                    if ($special_price < $temp_value && $special_price != null) {
                        $temp_value = $special_price;
                        $special_to_date = $this->_productRepository->getById($value)->getSpecialToDate();
                        $special_from_date = $this->_productRepository->getById($value)->getSpecialFromDate();
                    }
                }
            }
            if (!empty($special_price)) {
                $special_price = $temp_value;

                return [$special_from_date, $special_to_date];
            }
        } else {
            $special_price = $curent_product->getSpecialPrice();
            if ($special_price) {
                $special_to_date = $curent_product->getSpecialToDate();
                $special_from_date = $curent_product->getSpecialFromDate();
                return [$special_from_date, $special_to_date];
            }
        }

        return "";
    }

    public function getRulePriceFinishedSooner()
    {
        $groupId = $this->authContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);
        $catalogActiveRule = $this->ruleCollectionFactory->create()->addFieldToFilter('is_active', 1);
        if (!empty($catalogActiveRule)) {
            $returnDataTo = "2050-01-01";
            $returnDataFrom = "2050-01-01";
            $product = $this->_view->getProduct();
            $allRuleIds = $catalogActiveRule->getAllIds();
            foreach ($allRuleIds as $id) {
                $current_rule =  $catalogActiveRule->getItemById($id);
                $to_date = $current_rule->getData('to_date');
                $website_id = 1;
                $id_product = $product->getId();
                if ($product->canConfigure()) {
                    $arr = $this->_configurable->getChildrenIds($id_product)[0];
                    $first_key = array_key_first($arr);
                    $children_id = $arr[$first_key];
                }else {
                    $children_id = $id_product;
                }

                $check_in = $this->rule->getRulesFromProduct($to_date, $website_id, $groupId, $children_id);
                if ($check_in) {
                    $data_from = $catalogActiveRule->getItemById($id)->getData('from_date');
                    $data_to = $catalogActiveRule->getItemById($id)->getData('to_date');

                    if (strtotime($data_to) < strtotime($returnDataTo)) {
                        $returnDataTo = str_replace("-", "/", $data_to);
                        $returnDataFrom = str_replace("-", "/", $data_from);
                    }
                } else {
                    return false;
                }
            }
            return [$returnDataFrom, $returnDataTo];
        }
        return false;
    }

    public function getSpecial()
    {
        $arr = $this->getSpecialPriceInformation();
        $time_now = time();
        $show_block_check1 = false;
        if ($arr != "" && $arr[1] != null) {
            $special_from_date = strtotime($arr[0]);
            $special_to_date = strtotime($arr[1]);
            if ($special_from_date <= $time_now) {
                $right_date_special = substr(str_replace("-", "/", $arr[1]), 0, 10);
                $text_event_special = "special price";
                $show_block_check1 = true;
                return ['date_special_to' => $right_date_special, 'text_special' => $text_event_special, 'show_special' => $show_block_check1];
            }
        }
        $right_date_special = "2050/11/12";
        $text_event_special = "";

        return ['date_special_to' => $right_date_special, 'text_special' => $text_event_special, 'show_special' => $show_block_check1];
    }

    public function getRule()
    {
        $rule_prices_arr = $this->getRulePriceFinishedSooner();
        $show_block_check2 = false;

        if ($rule_prices_arr && $rule_prices_arr[1] != null) {
            $rule_from_date = strtotime($rule_prices_arr[0]);
            $rule_to_date = strtotime($rule_prices_arr[1]);
            if ($rule_from_date <= $rule_to_date) {
                $right_date_rule = $rule_prices_arr[1];
                $text_event_rule = "catalog rule price";
                $show_block_check2 = true;
                return ['date_rule_to' => $right_date_rule, 'text_rule' => $text_event_rule, 'show_rule' => $show_block_check2];
            }
        }
        $right_date_rule = "2050/12/12";
        $text_event_rule = "";
        return ['date_rule_to' => $right_date_rule, 'text_rule' => $text_event_rule, 'show_rule' => $show_block_check2];
    }

    public function selectShorterDate($special_arr, $rule_arr)
    {
        if ($special_arr['show_special'] && $rule_arr['show_rule']) {
            if (strtotime($special_arr['date_special_to']) > strtotime($rule_arr['date_rule_to'])) {
                $right_date = $rule_arr['date_rule_to'];
                $text_event = $rule_arr['text_rule'];
            } else {
                $right_date = $special_arr['date_special_to'];
                $text_event = $special_arr['text_special'];
            }
        } elseif ($special_arr['show_special'] && !$rule_arr['show_rule']) {
            $right_date = $special_arr['date_special_to'];
            $text_event = $special_arr['text_special'];
        } elseif (!$special_arr['show_special'] && $rule_arr['show_rule']) {
            $right_date = $rule_arr['date_rule_to'];
            $text_event = $rule_arr['text_rule'];
        } else {
            $right_date = "";
            $text_event = "";
        }

        return ['right_date' => $right_date, 'right_text' => $text_event];
    }

    public function getAllCatalogRulePrices()
    {
        $catalogActiveRule = $this->ruleCollectionFactory->create()->addFieldToFilter('is_active', 1);
        if (!empty($catalogActiveRule)) {
            $arr_rules = [];
            $allRuleIds = $catalogActiveRule->getAllIds();
            foreach ($allRuleIds as $id) {
                $current_rule =  $catalogActiveRule->getItemById($id);
                $name = $current_rule->getData('name');
                $to_date = $current_rule->getData('to_date');
                $arr_rules [] = ['name' => $name, 'to_date' => $to_date];
            }
            return $arr_rules;
        }else {
            return false;
        }
    }
}
