<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Controller\Adminhtml\Shopfinder;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Anee\Shopfinder\Controller\Adminhtml\Shopfinder as MainShopfinder;
use Magento\Framework\Controller\ResultInterface;

class Delete extends MainShopfinder implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                $this->shopfinderRepository->deleteById((int)$id);
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the shop.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a shop to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
