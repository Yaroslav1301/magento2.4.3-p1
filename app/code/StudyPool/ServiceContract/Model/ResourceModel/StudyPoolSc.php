<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class StudyPoolSc
 *
 * @package StudyPool\ServiceContract\Model\ResourceModel
 */
class StudyPoolSc extends AbstractDb
{
    /**
     * Resource initialization.
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('studypool_servicecontract_sc', 'entity_id');
    }
}
