<?php

declare(strict_types=1);

namespace StudyPool\ServiceContract\Api\Data;


/**
 * Interface StudyPoolScInterface
 *
 * @package StudyPool\ServiceContract\Api\Data
 */
interface StudyPoolScInterface
{
    public const ENTITY_ID = 'entity_id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const IS_ACTIVE = 'is_active';
    public const CREATED_AT = 'created_at';
    public const PATH_URL_TO_MEDIA = 'media_url';

    /**
     * Get entity id
     *
     * @return int
     */
    public function getEntityId(): int;

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
     * @return string
     */
    public function getDescription(): string;

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription(string $description): void;

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
     * Set created at
     *
     * @param string|null $date
     */
    public function setCreatedAt(?string $date): void;

    /**
     * get media url
     * @return string|null
     */
    public function getUrlMedia(): ?string;

    /**
     * set url media
     * @param string|null $urlMedia
     */
    public function setUrlMedia(?string $urlMedia): void;
}
