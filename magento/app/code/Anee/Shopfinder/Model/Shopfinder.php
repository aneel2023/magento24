<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Model;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Magento\Framework\Model\AbstractModel;
use Anee\Shopfinder\Model\ResourceModel\Shopfinder as ShopfinderResource;
class Shopfinder extends AbstractModel implements ShopfinderInterface
{
    /**
     * Shopfinder cache tag
     */
    public const CACHE_TAG = 'anee_shopfinder';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'anee_shopfinder';

    /**
     * Construct.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShopfinderResource::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Set Shop ID
     * @param int $entityId
     * @return ShopfinderInterface
     */
    public function setEntityId($entityId): ShopfinderInterface
    {
        return $this->setData(self::ENTITY_ID, (int)$entityId);
    }

    /**
     * Retrieve shop id
     *
     * @return int|null
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(self::ENTITY_ID);
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return ShopfinderInterface
     */
    public function setIdentifier(string $identifier): ShopfinderInterface
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Retrieve shop identifier
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return (string)$this->getData(self::IDENTIFIER);
    }

    /**
     * Set title
     *
     * @param string $name
     * @return ShopfinderInterface
     */
    public function setName(string $name): ShopfinderInterface
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Retrieve shop name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set country
     *
     * @param string $country
     * @return ShopfinderInterface
     */
    public function setCountry(string $country): ShopfinderInterface
    {
        return $this->setData(self::COUNTRY, $country);
    }

    /**
     * Retrieve shop country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->getData(self::COUNTRY);
    }

    /**
     * Set longitude
     *
     * @param string|null $longitude
     * @return ShopfinderInterface
     */
    public function setLongitude(?string $longitude): ShopfinderInterface
    {
        return $this->setData(self::LONGITUDE, $longitude);
    }

    /**
     * Retrieve shop longitude
     *
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->getData(self::LONGITUDE);
    }

    /**
     * Set longitude
     *
     * @param string|null $latitude
     * @return ShopfinderInterface
     */
    public function setLatitude(?string $latitude): ShopfinderInterface
    {
        return $this->setData(self::LATITUDE, $latitude);
    }

    /**
     * Retrieve shop latitude
     *
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->getData(self::LATITUDE);
    }

    /**
     * Get image
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Set image
     * @param string|null $image
     * @return ShopfinderInterface
     */
    public function setImage(?string $image): ShopfinderInterface
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Get created at
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Get updated at
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }
}
