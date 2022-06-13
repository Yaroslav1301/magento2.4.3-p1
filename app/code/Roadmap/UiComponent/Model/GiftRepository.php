<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Roadmap\UiComponent\Api\GiftRepositoryInterface;
use Roadmap\UiComponent\Api\Data\GiftInterface;
use Roadmap\UiComponent\Api\Data\GiftInterfaceFactory;
use Roadmap\UiComponent\Model\ResourceModel\Gift as ResourceModel;
use Roadmap\UiComponent\Model\ResourceModel\Gift\Collection as GiftCollection;
use Roadmap\UiComponent\Model\ResourceModel\Gift\CollectionFactory as GiftCollectionFactory;

class GiftRepository implements GiftRepositoryInterface
{
    /**
     * @var GiftInterfaceFactory
     */
    private $giftFactory;

    /**
     * @var ResourceModel
     */
    private $giftResourceModel;

    /**
     * @var GiftCollectionFactory
     */
    private $giftCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var SearchResultsInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @param GiftInterfaceFactory $giftInterfaceFactory
     * @param ResourceModel $giftResourceModel
     * @param GiftCollectionFactory $giftCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultsInterfaceFactory $searchResultFactory
     */
    public function __construct(
        GiftInterfaceFactory $giftInterfaceFactory,
        ResourceModel $giftResourceModel,
        GiftCollectionFactory $giftCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchResultsInterfaceFactory $searchResultFactory
    ) {
        $this->giftFactory = $giftInterfaceFactory;
        $this->giftResourceModel = $giftResourceModel;
        $this->giftCollectionFactory = $giftCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheirtDoc
     *
     * @throws NoSuchEntityException
     */
    public function getById(int $giftId): GiftInterface
    {
        /** @var GiftInterface $gift */
        $gift = $this->giftFactory->create();
        $this->giftResourceModel->load($gift, $giftId, 'gift_id');

        if (!$gift->getId()) {
            throw new NoSuchEntityException(__('Gift "%1" does not exist.', $gift));
        }

        return $gift;
    }

    /**
     * @inheritDoc
     *
     * @throws CouldNotSaveException
     */
    public function save(GiftInterface $giftEntity): GiftInterface
    {
        try {
            $this->giftResourceModel->save($giftEntity);
        } catch (\Exception | AlreadyExistsException $exception) {
            throw new CouldNotSaveException(
                __('Could not save the object: %1', $exception->getMessage()),
                $exception
            );
        }
        return $giftEntity;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface
    {
        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->searchResultFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        /** @var GiftCollection $collection */
        $collection = $this->giftCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $giftId): bool
    {
        try {
            $gift = $this->getById($giftId);
            $this->giftResourceModel->delete($gift);
        } catch (NoSuchEntityException | \Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the object: %1', $exception->getMessage()),
                $exception
            );
        }
        return true;
    }
}
