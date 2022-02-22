<?php

namespace Kozar\AddDeclaretive\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Kozar\AddDeclaretive\Api\Data\StudentExtensionInterface;
use Kozar\AddDeclaretive\Api\Data\StudentInterface;

class Student extends AbstractExtensibleModel implements StudentInterface
{

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function setId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return parent::getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getAge()
    {
        return parent::getData(self::AGE);
    }

    /**
     * @inheritDoc
     */
    public function setAge($age)
    {
        return $this->setData(self::AGE, $age);
    }
    /**
     * @inheritDoc
     */
    public function getGraduated()
    {
        return parent::getData(self::IS_GRADUATED);
    }
    /**
     * @inheritDoc
     */
    public function setGraduated($isGraduated)
    {
        return parent::setData(self::IS_GRADUATED, $isGraduated);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);
    }
    /**
     * @inheritDoc
     */
    public function getUpdatedAt()
    {
        return parent::getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @inheritDoc
     */
    public function setExtensionAttributes(
        StudentExtensionInterface $extensionAttributes
    ) {
        $this->_setExtensionAttributes($extensionAttributes);
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\Student::class);
    }
}
