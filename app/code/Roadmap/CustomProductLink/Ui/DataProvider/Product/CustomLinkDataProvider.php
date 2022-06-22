<?php

namespace Roadmap\CustomProductLink\Ui\DataProvider\Product;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Ui\DataProvider\Product\Related\AbstractDataProvider;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Roadmap\CustomProductLink\Model\Product\Link;

class CustomLinkDataProvider extends AbstractDataProvider
{
    /**
     * {@inheritdoc}
     */
    protected function getLinkType()
    {
        return Link::LINK_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = parent::getCollection();
        $collection->addAttributeToSelect('status');

        if ($this->getStore()) {
            $collection->setStore($this->getStore());
        }

        if (!$this->getProduct()) {
            return $collection;
        }

        $collection->addAttributeToFilter(
            $collection->getIdFieldName(),
            ['nin' => [$this->getProduct()->getId()]]
        );

        $collection->setVisibility(
            $this->getVisibleInSiteIds()
        );

        return $this->addCollectionFilters($collection);
    }

    /**
     * Return visible site ids
     *
     * @return array
     */
    private function getVisibleInSiteIds()
    {
        return [
            Visibility::VISIBILITY_IN_CATALOG,
            Visibility::VISIBILITY_BOTH
        ];
    }
}
