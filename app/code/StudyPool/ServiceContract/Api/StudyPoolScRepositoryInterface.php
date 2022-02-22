<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface StudyPoolScRepositoryInterface
 *
 * @package StudyPool\ServiceContract\Api
 */
interface StudyPoolScRepositoryInterface
{
    /**
     * Load studyPoolId by id
     *
     * @param int $studyPoolId
     * @return StudyPoolScInterface
     */
    public function getById(int $studyPoolId): StudyPoolScInterface;

    /**
     * Save studyPoolEntity data
     *
     * @param StudyPoolScInterface $studyPoolEntity
     * @return StudyPoolScInterface
     */
    public function save(StudyPoolScInterface $studyPoolEntity): StudyPoolScInterface;

    /**
     * Retrieve List which match a specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete studyPoolId by id
     *
     * @param int $studyPoolId
     *
     * @return StudyPoolScInterface
     *
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $studyPoolId): StudyPoolScInterface;
}
