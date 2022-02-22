<?php

namespace Kozar\AddCancelOrder\Controller\Order;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Class Cancel
 *
 * @package Kozar\AddCancelOrder\Controller\Order
 *
 */

class Cancel extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\App\ResourceConnection $resource
     */
    protected $resource;
    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $connection;
    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    protected $orderRepository;

    public function __construct(
        ResourceConnection $resource,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        Context $context
    ) {
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->orderRepository = $orderRepository;
        parent::__construct($context);
    }

    protected function getId($idFromRequest)
    {

        return preg_replace("#\#0+([1-9]*[0-9]+)#", "$1", $idFromRequest);
    }

    public function execute()
    {
        $id = (int)$this->getId($_REQUEST['id']);
        $stringId = $_REQUEST['id'];
        $comment = $_REQUEST['comment'];
        $reason = $_REQUEST['reason'];
        $result = $this->orderRepository->get($id-1);
        $result->cancel();
        $this->orderRepository->save($result);
        $this->insertMultiple($stringId, $reason, $comment, $id);
    }

    public function insertMultiple($orderId, $reason, $comment, $action)
    {
        $data = [
            [
                'order_id' => $orderId,
                'reason' => $reason,
                'comment' => $comment,
                'action' => $action,
                'cancled_by' => 'Customer']
        ];
        $tableName = 'canceled_orders_grid';
        $table = $this->resource->getTableName($tableName);
        $this->connection->insertMultiple($table, $data);
    }
}
