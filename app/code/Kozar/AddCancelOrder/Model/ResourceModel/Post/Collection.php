<?php
namespace Kozar\AddCancelOrder\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $idFieldName = 'id';
    protected $eventPrefix = 'canceled_orders_grid_collection';
    protected $eventObject = 'grid_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Kozar\AddCancelOrder\Model\Post', 'Kozar\AddCancelOrder\Model\ResourceModel\Post');
    }

}
