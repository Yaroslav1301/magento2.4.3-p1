<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterface;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterfaceFactory;
use StudyPool\ServiceContract\Api\StudyPoolScRepositoryInterface;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc as ResourceModel;
use StudyPool\ServiceContract\Model\StudyPoolScFactory;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc\Collection;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc\CollectionFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

/**
 * Class StudyPoolScRepository
 *
 * @package StudyPool\ServiceContract\Model
 */
class StudyPoolScRepository implements StudyPoolScRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    private $studyPoolResource;

    /**
     * @var StudyPoolScInterfaceFactory
     */
    private $studyPoolFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * StudyPoolScRepository constructor.
     *
     * @param ResourceModel $studyPoolResource
     * @param StudyPoolScInterfaceFactory $studyPoolFactory
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        ResourceModel $studyPoolResource,
        StudyPoolScInterfaceFactory $studyPoolFactory,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultFactory
    ) {
        $this->studyPoolResource = $studyPoolResource;
        $this->studyPoolFactory = $studyPoolFactory;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritDoc
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $studyPoolId): StudyPoolScInterface
    {
        /** @var StudyPoolScInterface $studyPool */
        $studyPool = $this->studyPoolFactory->create();
        $this->studyPoolResource->load($studyPool, $studyPoolId, 'entity_id');

        if (!$studyPool->getId()) {
            throw new NoSuchEntityException(__('StudyPoolEntity "%1" does not exist.', $studyPool));
        }

        return $studyPool;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(StudyPoolScInterface $studyPoolEntity): StudyPoolScInterface
    {
        try {
            $this->studyPoolResource->save($studyPoolEntity);
        } catch (\Throwable $throwable) {
            throw new CouldNotSaveException(
                __('Could not save the object: %1', $throwable->getMessage()),
                $throwable
            );
        }
        return $studyPoolEntity;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $studyPoolId): StudyPoolScInterface
    {
        try {
            $studyPool = $this->getById($studyPoolId);
            $this->studyPoolResource->delete($studyPool);
        } catch (\Throwable $throwable) {
            throw new CouldNotDeleteException(
                __('Could not delete the object: %1', $throwable->getMessage()),
                $throwable
            );
        }
        return $studyPool;
    }
}
