<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Controller\Adminhtml;

use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\App\Action;

abstract class Shopfinder extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Anee_Shopfinder::manage_shopfinder';

    /** @var ShopfinderRepositoryInterface */
    protected $shopfinderRepository;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ShopfinderRepositoryInterface$shopfinderRepository
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->shopfinderRepository = $shopfinderRepository;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage): Page
    {
        $resultPage->setActiveMenu('Anee_Shopfinder::manage_shopfinder')
            ->addBreadcrumb(__('Shopfinder'), __('Shopfinder'))
            ->addBreadcrumb(__('Shops'), __('Shops'));
        return $resultPage;
    }
}
