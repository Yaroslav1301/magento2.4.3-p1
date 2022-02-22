<?php


namespace Kozar\ResaveProducts\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    protected $loggerType = Logger::DEBUG;

    protected $fileName = '/var/log/resave_products.log';
}
