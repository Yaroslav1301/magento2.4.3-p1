<?php

/**
 * LiqPay Extension for Magento 2
 *
 * @author     Volodymyr Konstanchuk http://konstanchuk.com
 * @copyright  Copyright (c) 2017 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @updated Yaroslav Kozar 2021
 */

namespace LiqpayMagento\LiqPay\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;
use LiqpayMagento\LiqPay\Sdk\LiqPay;
use LiqpayMagento\LiqPay\Helper\Data as Helper;

class SubmitForm extends Template
{
    /**
     * @var null
     */
    protected $order = null;

    /* @var $liqPay LiqPay */
    protected $liqPay;

    /* @var $helper Helper */
    protected $helper;

    /**
     * SubmitForm constructor.
     * @param Template\Context $context
     * @param LiqPay $liqPay
     * @param Helper $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        LiqPay $liqPay,
        Helper $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->liqPay = $liqPay;
        $this->helper = $helper;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        if ($this->order === null) {
            throw new \Exception('Order is not set');
        }
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    protected function _toHtml()
    {
        $url = $this->getBaseUrl().'rest/V1/liqpay/callback';
        $order = $this->getOrder();
        $html = $this->liqPay->cnb_form([
            'action' => 'pay',
            'amount' => $order->getGrandTotal(),
            'currency' => $order->getOrderCurrencyCode(),
            'description' => $this->helper->getLiqPayDescription($order),
            'order_id' => $order->getIncrementId(),
            'result_url' => $url
        ]);
        return $html;
    }
}
