<?php

/**
 * LiqPay Extension for Magento 2
 * @author     Yaroslav Kozar
 * @copyright  Copyright (c) 2021 The authors
 */

namespace LiqpayMagento\LiqPay\Model;

use Magento\Framework\App\RequestInterface;

class RedirectPlugin
{
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;
    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var \LiqpayMagento\LiqPay\Sdk\LiqPay
     */
    protected $liqPay;

    /**
     * RedirectPlugin constructor.
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param RequestInterface $requestFromHttp
     * @param \LiqpayMagento\LiqPay\Sdk\LiqPay $liqPay
     */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        RequestInterface $requestFromHttp,
        \LiqpayMagento\LiqPay\Sdk\LiqPay $liqPay
    ) {
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
        $this->redirect = $redirect;
        $this->request = $requestFromHttp;
        $this->liqPay = $liqPay;
    }

    /**
     * @param \Magento\Webapi\Controller\Rest $subject
     * @param $request
     * @return \Magento\Framework\Controller\Result\Redirect|mixed|null
     */
    public function afterdispatch(\Magento\Webapi\Controller\Rest $subject, $request)
    {
        $check = (string)$request->getContent();
        if (str_contains($check, "canRedirectOnSuccessPage")) {
            $post = $this->request->getParams();
            if (!(isset($post['data']) && isset($post['signature']))) {
                return null;
            }

            $data = $post['data'];
            $decodedData = $this->liqPay->getDecodedData($data);
            $paymentId = $decodedData['payment_id'] ?? null;
            $resultRedirect = $this->resultFactory->create(
                \Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT
            );
            $this->messageManager->addSuccessMessage(__('Your paymnet id is '. $paymentId));
            return $resultRedirect->setPath('checkout/onepage/success');
        }

        return $request;
    }
}
