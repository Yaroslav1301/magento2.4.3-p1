<?php

namespace Kozar\ProductImagesUploader\Model\ResourceModel\Gallery;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Kozar\ProductImagesUploader\Model\Gallery', 'Kozar\ProductImagesUploader\Model\ResourceModel\Gallery');
    }
}
