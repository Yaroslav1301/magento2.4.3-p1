<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Model;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Registry;
use StudyPool\ServiceContract\Api\Data\StudyPoolScInterface;
use Magento\Framework\Model\AbstractModel;
use StudyPool\ServiceContract\Model\ResourceModel\StudyPoolSc as StudyPoolScResource;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;

/**
 * Class StudyPoolSc
 *
 * @package StudyPool\ServiceContract\Model
 */
class StudyPoolSc extends AbstractModel implements StudyPoolScInterface
{
    /**
     * @var DateTimeFactory
     */
    private $dateFactory;

    /**
     * StudyPoolSc constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param DateTimeFactory $dateFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        DateTimeFactory $dateFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->dateFactory = $dateFactory;
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(StudyPoolScResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int) $this->getData(self::ENTITY_ID);
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return (string) $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @inheritDoc
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * @inheritDoc
     */
    public function setIsActive(bool $status): void
    {
        $this->setData(self::IS_ACTIVE, $status);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(?string $date): void
    {
        $this->setData(self::CREATED_AT, $date);
    }

    /**
     * @inheritDoc
     */
    public function getUrlMedia(): ?string
    {
        return $this->getData(self::PATH_URL_TO_MEDIA);
    }

    /**
     * @inheritDoc
     */
    public function setUrlMedia(?string $urlMedia): void
    {
        $this->setData(self::PATH_URL_TO_MEDIA, $urlMedia);
    }

    /**
     * @inheritDoc
     */
    public function beforeSave(): StudyPoolSc
    {
        parent::beforeSave();

        $currentDate = $this->dateFactory->create()->gmtDate();
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt($currentDate);
        }

        return $this;
    }
}
