<?php
namespace Kozar\AddCancelOrder\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'canceled_orders_grid';

    protected $cacheTag = 'canceled_orders_grid';

    protected $eventPrefix = 'canceled_orders_grid';

    protected function _construct()
    {
        $this->_init('Kozar\AddCancelOrder\Model\ResourceModel\Post');
    }

    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues(): array
    {
        $values = [];

        return $values;
    }
}
