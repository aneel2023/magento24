<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Api;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface ShopfinderRepositoryInterface
{
    /**
     * Save shop.
     *
     * @param ShopfinderInterface $shopfinder
     * @return ShopfinderInterface
     * @throws LocalizedException
     */
    public function save(ShopfinderInterface $shopfinder): ShopfinderInterface;

    /**
     * Retrieve shop.
     *
     * @param int $id
     * @return ShopfinderInterface
     * @throws LocalizedException
     */
    public function getById(int $id): ShopfinderInterface;

    /**
     * Retrieve shops matching the specified criteria.
     *
     * @param SearchCriteria $searchCriteria
     * @return SearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteria $searchCriteria): SearchResultsInterface;

    /**
     * Retrieve shops matching the specified identifier.
     *
     * @param string $identifier
     * @return ShopfinderInterface
     * @throws NoSuchEntityException
     */
    public function getShopByIdentifier(string $identifier): ShopfinderInterface;

    /**
     * Delete shop.
     *
     * @param ShopfinderInterface $shopfinder
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(ShopfinderInterface $shopfinder): bool;

    /**
     * Delete shops by ID.
     *
     * @param int $id
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById(int $id): bool;
}
