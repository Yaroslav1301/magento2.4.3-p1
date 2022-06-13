<?php

declare(strict_types=1);

namespace Roadmap\UiComponent\Api\Data;

interface GiftInterface
{
    public const GIFT_ID = 'gift_id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const IS_ACTIVE = 'is_active';
    public const CREATED_AT = 'created_at';
    public const PATH_URL_TO_MEDIA = 'media_url';
    public const RELATED_PRODUCT_SKU = 'product_sku';

    /**
     * Get gift id
     *
     * @return int
     */
    public function getGiftId(): int;

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name
     *
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Set description
     *
     * @param string|null $description
     */
    public function setDescription(?string $description): void;

    /**
     * Check active status
     *
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Set active status
     *
     * @param bool $status
     */
    public function setIsActive(bool $status): void;

    /**
     * Get created at
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Get media url
     *
     * @return string|null
     */
    public function getMediaUrl(): ?string;

    /**
     * Set media url
     *
     * @param string|null $urlMedia
     */
    public function setMediaUrl(?string $urlMedia): void;

    /**
     * Get related product sku
     *
     * @return string
     */
    public function getRelatedProductSku(): string;

    /**
     * Set related product sku
     *
     * @param string $productSku
     * @return void
     */
    public function setRelatedProductSku(string $productSku): void;
}
