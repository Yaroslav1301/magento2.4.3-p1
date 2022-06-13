<?php

namespace Roadmap\UiComponent\Model\ResourceModel\Gift;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Roadmap\UiComponent\Model\Gift as Model;
use Roadmap\UiComponent\Model\ResourceModel\Gift as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gift_for_products_collection';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
