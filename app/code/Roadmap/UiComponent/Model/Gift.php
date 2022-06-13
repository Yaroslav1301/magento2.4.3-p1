<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Model;

use Magento\Framework\Model\AbstractModel;
use Roadmap\UiComponent\Model\ResourceModel\Gift as ResourceModel;
use Roadmap\UiComponent\Api\Data\GiftInterface;

class Gift extends AbstractModel implements GiftInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'gift_for_products_model';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getGiftId(): int
    {
        return (int) $this->getData(self::GIFT_ID);
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
    public function getDescription(): ?string
    {
        return (string) $this->getData(self::DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function setDescription(?string $description): void
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
    public function getMediaUrl(): ?string
    {
        return $this->getData(self::PATH_URL_TO_MEDIA);
    }

    /**
     * @inheritDoc
     */
    public function setMediaUrl(?string $urlMedia): void
    {
        $this->setData(self::PATH_URL_TO_MEDIA, $urlMedia);
    }

    /**
     * @inheritDoc
     */
    public function getRelatedProductSku(): string
    {
        return $this->getData(self::RELATED_PRODUCT_SKU);
    }

    /**
     * @inheritDoc
     */
    public function setRelatedProductSku(string $productSku): void
    {
        $this->setData(self::RELATED_PRODUCT_SKU, $productSku);
    }
}
