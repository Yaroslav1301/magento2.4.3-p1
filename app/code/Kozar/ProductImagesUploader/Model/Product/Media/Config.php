<?php

namespace Kozar\ProductImagesUploader\Model\Product\Media;

class Config extends \Magento\Catalog\Model\Product\Media\Config
{
    /**
     * Filesystem directory path of  images relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaPathAddition()
    {
        return 'kozar';
    }

    /**
     * Web-based directory path of  images relatively to media folder
     *
     * @return string
     */
    public function getBaseMediaUrlAddition()
    {
        return 'kozar';
    }

    /**
     * @return string
     */
    public function getBaseMediaPath()
    {
        return 'kozar';
    }

    /**
     * @return string
     */
    public function getBaseMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'kozar';
    }
}
