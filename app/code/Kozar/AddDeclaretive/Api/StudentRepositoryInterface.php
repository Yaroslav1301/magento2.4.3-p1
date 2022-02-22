<?php

namespace Kozar\AddDeclaretive\Api;

interface StudentRepositoryInterface
{
    /**
     * @param int $id
     * @return \Kozar\AddDeclaretive\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Kozar\AddDeclaretive\Api\Data\StudentInterface $student
     * @return \Kozar\AddDeclaretive\Api\Data\StudentInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Kozar\AddDeclaretive\Api\Data\StudentInterface $student);

    /**
     * @param \Kozar\AddDeclaretive\Api\Data\StudentInterface $student
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Kozar\AddDeclaretive\Api\Data\StudentInterface $student);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Kozar\AddDeclaretive\Api\Data\StudentSearchResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
