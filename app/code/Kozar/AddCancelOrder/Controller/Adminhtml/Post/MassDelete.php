<?php

namespace Kozar\AddCancelOrder\Controller\Adminhtml\Post;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Kozar\AddCancelOrder\Model\ResourceModel\Post\CollectionFactory;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\ResourceConnection $resource
     */
    protected $resource;
    protected $connection;
    protected $_request;
    protected $_filter;
    protected $_collectionFactory;

    /**
     * MassDelete constructor.
     * @param CollectionFactory $collectionFactory
     * @param ResourceConnection $resource
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\RequestInterface $request
     * @param Filter $filter
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceConnection $resource,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\RequestInterface $request,
        Filter $filter
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_filter = $filter;
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->_request = $request;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $tableName = 'canceled_orders_grid';
        $table = $this->resource->getTableName($tableName);
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        foreach ($collection->getItems() as $record) {
            $id = (int)$record->getData('action');
            $this->connection->delete($table, ['action = ?' => $id]);
        }
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
