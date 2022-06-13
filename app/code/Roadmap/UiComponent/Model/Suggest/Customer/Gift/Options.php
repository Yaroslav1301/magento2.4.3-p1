<?php

namespace Roadmap\UiComponent\Model\Suggest\Customer\Gift;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $products = [];

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        CollectionFactory $productCollectionFactory
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        /**
         * @var Collection $collection
         */
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect('sku');

        $options = [];
        foreach ($collection as $item) {
            $options[] = [
                'label' => $item->getSku(),
                'value' => $item->getSku()
            ];
        }
        return $options;
    }
}
