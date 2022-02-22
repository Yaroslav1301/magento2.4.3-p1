<?php

namespace Kozar\AddCustomPriceAtr\Plugins;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class Product
{
    protected $_configurable;

    protected $_productCollectionFactory;

    protected $_request;

    protected $_helper;

    public function __construct(
        \Kozar\AddCustomPriceAtr\Helper\Data $_helper,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        Configurable $configurable
    ) {
        $this->_configurable = $configurable;
        $this->_request = $request;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_helper = $_helper;
    }

    public function aftergetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        $enable_custom_price = $this->_helper->getGeneralConfig('enable');
        if ($enable_custom_price) {
            if ($this->_request->getFullActionName() == 'catalog_product_view') {
                return $result;
            } else {
                $id = $subject->getId();
                $collection = $this->_productCollectionFactory->create();
                $collection->addFieldToFilter('allow_modify', '1');
                $collection->addAttributeToSelect(['allow_modify', 'custom_price']);
                $allow_modify_arr = $collection->getData();
                $custom_prices_arr = $collection->getAllAttributeValues('custom_price');

                if ($subject->canConfigure()) {
                    $children_ids = $this->_configurable->getChildrenIds($subject->getId());
                    foreach ($children_ids[0] as $child_id) {
                        foreach ($custom_prices_arr as $key => $value) {
                            if ($key == $child_id) {
                                return $value[0];
                            }
                        }
                    }
                }

                $parent_id = $this->_configurable->getParentIdsByChild($id);
                if (isset($parent_id[0])) {
                    $children_ids = $this->_configurable->getChildrenIds($parent_id);
                    foreach ($children_ids[0] as $child_id) {
                        foreach ($custom_prices_arr as $key => $value) {
                            if ($key == $child_id) {
                                return $value[0];
                            }
                        }
                    }
                }

                foreach ($custom_prices_arr as $key => $value) {
                    if ($key == $id) {
                        return $value[0];
                    }
                }

                return $result;
            }
        } else {
            return $result;
        }
    }
}
