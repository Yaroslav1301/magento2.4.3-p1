<?php

namespace Roadmap\UiComponent\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Gift extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gift_for_products_resource_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init('gift_for_products', 'gift_id');
    }
}
