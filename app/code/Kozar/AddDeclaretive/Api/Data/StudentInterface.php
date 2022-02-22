<?php
namespace Kozar\AddDeclaretive\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface StudentInterface extends ExtensibleDataInterface
{
    const ENTITY_ID = 'entity_id';
    const NAME = 'name';
    const AGE = 'age';
    const IS_GRADUATED = 'is_graduated';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return int
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name
     * @return string
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getAge();

    /**
     * @param $age
     * @return int
     */
    public function setAge($age);

    /**
     * @return int
     */
    public function getGraduated();

    /**
     * @param $isGraduated
     * @return int
     */
    public function setGraduated($isGraduated);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @return Kozar\AddDeclaretive\Api\Data\StudentExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param Kozar\AddDeclaretive\Api\Data\StudentExtensionInterface $extensionAttributes
     * @return mixed
     */
    public function setExtensionAttributes(StudentExtensionInterface $extensionAttributes);
}
