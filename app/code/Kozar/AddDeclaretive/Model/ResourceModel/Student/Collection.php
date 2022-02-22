<?php

namespace Kozar\AddDeclaretive\Model\ResourceModel\Student;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Kozar\AddDeclaretive\Model\ResourceModel\Student;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Kozar\AddDeclaretive\Model\Student::class,
            Student::class
        );
    }
}
