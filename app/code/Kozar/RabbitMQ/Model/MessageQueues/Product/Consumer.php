<?php declare(strict_types=1);

namespace Kozar\RabbitMQ\Model\MessageQueues\Product;

/**
 * Class Consumer
 * @package Kozar\RabbitMQ\MessageQueues\Product
 */
class Consumer
{
     /**
     * Consumer constructor.
     */
     public function __construct()
     {

     }

     public function processMessage(string $_data)
     {

         // do something with message queue data
         echo $_data."\n";

         $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
         $logger = new \Zend\Log\Logger();
         $logger->addWriter($writer);
         $logger->info($_data);

     }

}
