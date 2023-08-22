<?php
declare(strict_types=1);
/**
 * Copyright © Anee, Inc. All rights reserved.
 */

namespace Anee\Shopfinder\Model;

use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Api\Data\ShopfinderSearchResultsInterfaceFactory;
use Anee\Shopfinder\Model\ResourceModel\Shopfinder as ResourceShopfinder;
use Anee\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory as ShopfinderCollectionFactory;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteria\CollectionProcessor;

class ShopfinderRepository implements ShopfinderRepositoryInterface
{
    /** @var ResourceShopfinder  */
    protected $resource;

    /** @var ShopfinderFactory  */
    protected $shopfinderFactory;

    /** @var ShopfinderCollectionFactory  */
    protected $shopfinderCollectionFactory;

    /** @var ShopfinderSearchResultsInterfaceFactory  */
    protected $searchResultsFactory;

    /** @var CollectionProcessor  */
    private $collectionProcessor;

    /** @var SearchCriteriaBuilder  */
    private $searchCriteriaBuilder;

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResourceShopfinder $resource,
        ShopfinderFactory $shopfinderFactory,
        ShopfinderCollectionFactory $shopfinderCollectionFactory,
        ShopfinderSearchResultsInterfaceFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CollectionProcessor $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->shopfinderFactory = $shopfinderFactory;
        $this->shopfinderCollectionFactory = $shopfinderCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * Save Shopfinder data
     *
     * @param ShopfinderInterface $shopfinder
     * @return ShopfinderInterface
     * @throws CouldNotSaveException
     */
    public function save(ShopfinderInterface $shopfinder): ShopfinderInterface
    {
        try {
            $model = $this->shopfinderFactory->create();
            $model->setData($shopfinder->getData());
            $this->resource->save($model);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $model;
    }

    /**
     * Load shop data by given id
     * @param int $id
     * @return ShopfinderInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): ShopfinderInterface
    {
        /** @var Shopfinder $shopfinder */
        $shopfinder = $this->shopfinderFactory->create();
        $this->resource->load($shopfinder, $id, 'entity_id');
        if (!$shopfinder->getId()) {
            throw new NoSuchEntityException(__('The shop with the "%1" id doesn\'t exist.', $id));
        }
        return $shopfinder;
    }

    /**
     * Load shops data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param SearchCriteria $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteria $searchCriteria): SearchResultsInterface
    {
        /** @var \Anee\Shopfinder\Model\ResourceModel\Shopfinder\Collection $collection */
        $collection = $this->shopfinderCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Retrieve shops matching the specified identifier.
     *
     * @param string $identifier
     * @return ShopfinderInterface
     * @throws NoSuchEntityException
     */
    public function getShopByIdentifier(string $identifier): ShopfinderInterface
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(ShopfinderInterface::IDENTIFIER, $identifier)
            ->create();
        $data = $this->getList($searchCriteria);
        if ($data->getTotalCount() === 0) {
            throw new NoSuchEntityException(
                __('The shop with the "%1" identifier doesn\'t exist.', $identifier)
            );
        }
        $items = $data->getItems();
        /** @var ShopfinderInterface $item */
        $item = reset($items);
        return $item;
    }

    /**
     * Delete shop
     *
     * @param ShopfinderInterface $shopfinder
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(ShopfinderInterface $shopfinder): bool
    {
        try {
            $this->resource->delete($shopfinder);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete shop by given id
     *
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        return $this->delete($this->getById($id));
    }
}
