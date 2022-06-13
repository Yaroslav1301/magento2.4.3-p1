<?php

namespace Roadmap\UiComponent\Ui\Component\Form\Product;

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
        $options = [];

        $productCollection = $this->getCollection('configurable');
        $options[] = $this->getOptionGroup($productCollection, "Configurable Products", "Configurable Products");

        $bundleCollection = $this->getCollection('bundle');
        $options[] = $this->getOptionGroup($bundleCollection, "Bundle Products", "Bundle Products");

        $downloadableCollection = $this->getCollection('downloadable');
        $options[] = $this->getOptionGroup($downloadableCollection, "Downloadable Products", "Downloadable Products");

        $downloadableCollection = $this->getCollection('grouped');
        $options[] = $this->getOptionGroup($downloadableCollection, "Grouped Products", "Grouped Products");

        $simpleCollection = $this->getCollection('simple');
        $options[] = $this->getOptionGroup($simpleCollection, "Simple Products", "Simple Products");

        return $options;
    }

    /**
     * @param Collection $collection
     * @param $label
     * @param $value
     * @return array
     */
    protected function getOptionGroup(Collection $collection, $label, $value)
    {
        $options = [
            'label' => $label,
            'value' => $value
        ];

        $index = 0;

        foreach ($collection as $item) {
            $options['optgroup'][$index] = [
                'label' => $item->getSku(),
                'value' => $item->getSku()
            ];
            $index++;
        }

        return $options;
    }

    /**
     * @param string $typeId
     * @return Collection
     */
    protected function getCollection($typeId)
    {
        return $this->productCollectionFactory->create()
            ->addAttributeToSelect(['type_id', 'sku'])
            ->addAttributeToFilter('type_id', ['like' => $typeId]);
    }

}
