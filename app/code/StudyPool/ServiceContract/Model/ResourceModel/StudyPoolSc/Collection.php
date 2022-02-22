<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use StudyPool\ServiceContract\Model\StudyPoolSc as StudyPoolScModel;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc as StudyPoolScResourceModel;

/**
 * Class Collection
 *
 * @package StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc
 */
class Collection extends AbstractCollection
{
    /**
     * SimpleUi resource collection initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            StudyPoolScModel::class,
            StudyPoolScResourceModel::class
        );
    }
}
