<?php

namespace Kozar\ProductImagesUploader\Model;

class Gallery extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Define resource model
     */

    protected function _construct()
    {
        $this->_init('Kozar\ProductImagesUploader\Model\ResourceModel\Gallery');
    }
}
