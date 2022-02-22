<?php
namespace Magefox\Example\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper{

    public function upperString($string) {
        return strtoupper($string);
    }
}
