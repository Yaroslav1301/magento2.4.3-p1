<?php

namespace Roadmap\CustomProductLink\Model\ProductLink\CollectionProvider;

class CustomLinkProducts implements \Magento\Catalog\Model\ProductLink\CollectionProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLinkedProducts($product)
    {
        return $product->getCustomLinkProducts();
    }
}
