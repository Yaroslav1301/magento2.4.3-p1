<?php

namespace Roadmap\CustomProductLink\Model;

use Magento\Catalog\Model\Product as DefaultProduct;
use Roadmap\CustomProductLink\Model\Product\Link;

class Product extends DefaultProduct
{

    /**
     * Retrieve array of customlink products
     *
     * @return array
     */
    public function getCustomLinkProducts()
    {
        if (!$this->hasCustomLinkProducts()) {
            $products = [];
            $collection = $this->getCustomLinkProductCollection();
            foreach ($collection as $product) {
                $products[] = $product;
            }
            $this->setCustomLinkProducts($products);
        }
        return $this->getData('custom_link_products');
    }

    /**
     * Retrieve customlink products identifiers
     *
     * @return array
     */
    public function getCustomLinkProductIds()
    {
        if (!$this->hasCustomLinkProductIds()) {
            $ids = [];
            foreach ($this->getCustomLinkProducts() as $product) {
                $ids[] = $product->getId();
            }
            $this->setCustomLinkProductIds($ids);
        }
        return [$this->getData('custom_link_product_ids')];
    }

    /**
     * Retrieve collection customlink product
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getCustomLinkProductCollection()
    {
        $collection = $this->getLinkInstance()->setLinkTypeId(
            Link::LINK_TYPE_CUSTOMLINK
        )->getProductCollection()->setIsStrongMode();
        $collection->setProduct($this);

        return $collection;
    }
}
