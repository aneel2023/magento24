<?php
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ShopfinderSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get shop list.
     *
     * @return \Anee\Shopfinder\Api\Data\ShopfinderInterface[]
     */
    public function getItems();

    /**
     * Set shop list.
     *
     * @param \Anee\Shopfinder\Api\Data\ShopfinderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
