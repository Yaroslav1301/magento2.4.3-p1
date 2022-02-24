<?php

namespace Roadmap\CustomizeCheckoutStep\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * This class adds customer_comment variable to email template
 */
class AddCommentToTemplate implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $transportObject = $observer->getEvent()->getData('transportObject');
        $order = $transportObject->getOrder();
        $customer_comment = null;
        if (isset($order)) {
            $customer_comment = $order->getCustomerComment();
        }
        $transportObject->setData('customer_comment', $customer_comment);
    }
}
