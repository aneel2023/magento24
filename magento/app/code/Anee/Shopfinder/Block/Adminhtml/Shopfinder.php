<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Block\Adminhtml;
use Magento\Backend\Block\Widget\Grid\Container;

class Shopfinder extends Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Shopfinder_Shopfinder';
        $this->_controller = 'adminhtml_block';
        $this->_headerText = __('Shoprfinder');
        $this->_addButtonLabel = __('Add New Shop');
        parent::_construct();
    }
}
