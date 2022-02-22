<?php
namespace Kozar\HelloWorld2\Model;
class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'kozar_helloworld_post';

    protected $_cacheTag = 'kozar_helloworld_post';

    protected $_eventPrefix = 'kozar_helloworld_post';

    protected function _construct()
    {
        $this->_init('Kozar\HelloWorld2\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
