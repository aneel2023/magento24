<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Controller\Adminhtml\Shopfinder;

use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Anee\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Anee_Shopfinder::manage_shopfinder';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /** @var ShopfinderRepositoryInterface */
    private $shopfinderRepository;

    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        ShopfinderRepositoryInterface $shopfinderRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->shopfinderRepository = $shopfinderRepository;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     * @throws LocalizedException|Exception
     */
    public function execute(): Redirect
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $shopDataDeleted = 0;
        $shopDataDeletedError = 0;
        foreach ($collection as $shop) {
            try {
                $this->shopfinderRepository->deleteById((int)$shop->getId());
                $shopDataDeleted++;
            } catch (LocalizedException $exception) {
                $shopDataDeletedError++;
            }
        }

        if ($shopDataDeleted) {
            $this->messageManager->addSuccessMessage(
                (string)__('A total of %1 record(s) have been deleted.', $shopDataDeleted)
            );
        }

        if ($shopDataDeletedError) {
            $this->messageManager->addErrorMessage(
                (string)__(
                    'A total of %1 record(s) haven\'t been deleted. Please see server logs for more details.',
                    $shopDataDeletedError
                )
            );
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
