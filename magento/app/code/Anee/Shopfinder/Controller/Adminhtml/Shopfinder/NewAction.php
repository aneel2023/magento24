<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Controller\Adminhtml\Shopfinder;

use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Controller\Adminhtml\Shopfinder as MainShopFinder;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;

class NewAction extends MainShopFinder implements HttpGetActionInterface
{
    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ForwardFactory $resultForwardFactory,
        ShopfinderRepositoryInterface $shopfinderRepository
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context, $coreRegistry, $shopfinderRepository);
    }

    /**
     * Create new CMS block
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
