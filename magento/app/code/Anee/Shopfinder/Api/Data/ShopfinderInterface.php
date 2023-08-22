<?php
declare(strict_types=1);

/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Api\Data;


interface ShopfinderInterface
{
    public const ENTITY_ID      = 'entity_id';
    public const IDENTIFIER    = 'identifier';
    public const NAME         = 'name';
    public const COUNTRY       = 'country';
    public const IMAGE = 'image';
    public const LONGITUDE   = 'longitude';
    public const LATITUDE     = 'latitude';
    public const CREATED_AT     = 'created_at';
    public const UPDATED_AT     = 'updated_at';
    public const TABLE_NAME = 'anee_shopfinder';
    public const IMAGE_DIR_PATH = 'shopfinder/images/';
    /**
     * Set Shop ID
     * @param int $entityId
     * @return ShopfinderInterface
     */
    public function setEntityId($entityId): ShopfinderInterface;

    /**
     * Get Shop ID
     *
     * @return int
     */
    public function getEntityId(): int;

    /**
     * Set identifier
     *
     * @return ShopfinderInterface
     */
    public function setIdentifier(string $identifier): ShopfinderInterface;

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Set Shop Name
     *
     * @return ShopfinderInterface
     */
    public function setName(string $name): ShopfinderInterface;

    /**
     * Get Shop Name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Set country
     *
     * @return ShopfinderInterface
     */
    public function setCountry(string $country): ShopfinderInterface;

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry(): string;

    /**
     * Set longitude
     *
     * @return ShopfinderInterface
     */
    public function setLongitude(?string $longitude): ShopfinderInterface;

    /**
     * Get longitude
     *
     * @return string|null
     */
    public function getLongitude(): ?string;


    /**
     * Set latitude
     *
     * @return ShopfinderInterface
     */
    public function setLatitude(?string $latitude): ShopfinderInterface;

    /**
     * Get latitude
     *
     * @return string|null
     */
    public function getLatitude(): ?string;

    /**
     * Set image
     *
     * @return ShopfinderInterface
     */
    public function setImage(?string $image): ShopfinderInterface;

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage(): ?string;

    /**
     * Get image
     *
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * Get image
     *
     * @return string
     */
    public function getUpdatedAt(): string;
}
