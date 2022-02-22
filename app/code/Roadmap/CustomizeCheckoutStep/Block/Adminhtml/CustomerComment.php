<?php

namespace Roadmap\CustomizeCheckoutStep\Block\Adminhtml;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

class CustomerComment extends Template
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Template\Context $context,
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return int|null
     */
    protected function getOrderId()
    {
        return $this->getRequest()->getParam('order_id');
    }

    /**
     * @return string|null
     */
    public function getCustomerComment()
    {
        try {
            $order = $this->orderRepository->get($this->getOrderId());
            return $order->getData('customer_comment');
        } catch (InputException|NoSuchEntityException $exception) {
            $this->_logger->error($exception->getMessage());
        }
        return null;
    }
}
