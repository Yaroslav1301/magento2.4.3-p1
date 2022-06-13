<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Roadmap\UiComponent\Api\Data\GiftInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface GiftRepositoryInterface
{
    /**
     * Load gift by id
     *
     * @param int $giftId
     * @return GiftInterface
     */
    public function getById(int $giftId): GiftInterface;

    /**
     * Save gift data
     *
     * @param GiftInterface $giftEntity
     * @return GiftInterface
     */
    public function save(GiftInterface $giftEntity): GiftInterface;

    /**
     * Retrieve List which match a specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;

    /**
     * Delete gift by id
     *
     * @param int $giftId
     * @return bool if gift is removed
     *
     * @throws NoSuchEntityException
     * @throws CouldNotDeleteException
     */
    public function deleteById(int $giftId): bool;
}
