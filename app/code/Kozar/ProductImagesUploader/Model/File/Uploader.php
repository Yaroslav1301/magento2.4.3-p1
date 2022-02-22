<?php

namespace Kozar\ProductImagesUploader\Model\File;

class Uploader extends \Magento\MediaStorage\Model\File\Uploader
{
    /**
     * Flag, that defines should DB processing be skipped
     *
     * @var bool
     */
    protected $_skipDbProcessing = true;
}
