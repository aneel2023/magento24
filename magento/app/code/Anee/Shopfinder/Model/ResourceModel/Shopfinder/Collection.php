<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Model\ResourceModel\Shopfinder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Anee\Shopfinder\Model\ResourceModel\Shopfinder as ShopfinderResource;
use Anee\Shopfinder\Model\Shopfinder;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Shopfinder::class, ShopfinderResource::class);
        $this->_setIdFieldName($this->getResource()->getIdFieldName());
    }
}
