<?php

namespace Roadmap\CustomProductLink\Plugin;

class UpdateToCustomLinkModel
{
    /**
     * @var \Roadmap\CustomProductLink\Model\ProductFactory
     */
    private $catalogModel;

    /**
     * @param \Roadmap\CustomProductLink\Model\ProductFactory $catalogModel
     */
    public function __construct(
        \Roadmap\CustomProductLink\Model\ProductFactory $catalogModel
    ) {
        $this->catalogModel = $catalogModel;
    }

    /**
     * Before plugin to update model class
     *
     * @param \Roadmap\CustomProductLink\Model\ProductLink\CollectionProvider\CustomLinkProducts $subject
     * @param Object $product
     * @return array
     */
    public function beforeGetLinkedProducts(
        \Roadmap\CustomProductLink\Model\ProductLink\CollectionProvider\CustomLinkProducts $subject,
        $product
    ) {
        $currentProduct = $this->catalogModel->create()->load($product->getId());
        return [$currentProduct];
    }
}
