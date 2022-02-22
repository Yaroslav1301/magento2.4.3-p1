<?php

namespace Roadmap\CustomizeCheckoutStep\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;

class RenderComment extends Template
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
     * @return string|null
     */
    public function getCustomerComment()
    {
        try {
            $orderId = $this->getRequest()->getParam('order_id');
            if (isset($orderId)) {
                $order = $this->orderRepository->get($orderId);
                return $order->getCustomerComment();
            }
        } catch (InputException|NoSuchEntityException $exception) {
            $this->_logger->error($exception->getMessage());
            $this->_logger->error($exception->getTraceAsString());
        }
        return null;
    }
}
