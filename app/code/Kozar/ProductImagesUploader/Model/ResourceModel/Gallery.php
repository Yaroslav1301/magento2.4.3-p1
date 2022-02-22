<?php

namespace Kozar\ProductImagesUploader\Model\ResourceModel;

class Gallery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $dataObject;

    public function __construct(
        \Magento\Framework\DataObject $dataObject,
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $connectionName = null
    )
    {
        $this->dataObject = $dataObject;
        parent::__construct($context, $connectionName);
    }

    /**
     * Resource initialization
     * @return void
     */
    protected function _construct()
    {
        $this->_init('kozar_gallery', 'id');
    }

    /**
     * Insert gallery data to db and retrieve last id
     *
     * @param array $data
     * @return integer
     */
    public function insertGalleryData($data)
    {
        $connection = $this->getConnection();
        $this->dataObject->setData($data);
        $data = $this->_prepareDataForTable($this->dataObject, $this->getMainTable());
        $connection->insert($this->getMainTable(), $data);

        return $connection->lastInsertId($this->getMainTable());
    }


    public function deleteGalleryData($valueId)
    {
        if (is_array($valueId) && count($valueId) > 0) {
            $condition = $this->getConnection()->quoteInto('id IN(?)', $valueId);
        }elseif (!is_array($valueId)) {
            $condition = $this->getConnection()->quoteInto('id = ?', $valueId);
        }else {
            return $this;
        }

        $this->getConnection()->delete($this->getMainTable(),$condition);
        return $this;
    }
}
