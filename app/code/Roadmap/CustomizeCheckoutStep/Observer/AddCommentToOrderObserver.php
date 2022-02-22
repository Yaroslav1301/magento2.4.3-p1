<?php

namespace Roadmap\CustomizeCheckoutStep\Observer;

use Roadmap\CustomizeCheckoutStep\Setup\SchemaInformation;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class AddCommentToOrderObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
        $comment = $quote->getData(SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT);
        $order->setData(SchemaInformation::ATTRIBUTE_CUSTOMER_COMMENT, $comment);
    }
}
