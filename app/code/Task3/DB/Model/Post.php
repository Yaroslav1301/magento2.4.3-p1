<?php
namespace Task3\DB\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface {
    const CACHE_TAG = 'task3_db_post';
    protected $_cacheTag = 'task3_db_post';
    protected $_eventPrefix = 'task3_db_post';

    protected function _construct()
    {
        $this->_init('Task3\DB\Model\ResourceModel\Post');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues() {
        $values = [];
        return $values;
    }
}
