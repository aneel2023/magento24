<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

class Shopfinder extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShopfinderInterface::TABLE_NAME, ShopfinderInterface::ENTITY_ID);
    }
}
