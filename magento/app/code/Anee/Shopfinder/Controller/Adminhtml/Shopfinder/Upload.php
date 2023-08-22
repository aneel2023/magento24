<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Controller\Adminhtml\Shopfinder;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Anee\Shopfinder\Service\ImageUploaderService;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;

class Upload extends Action implements HttpPostActionInterface
{
    /** @var ImageUploaderService  */
    private $imageUploaderService;

    public function __construct(
        Context $context,
        ImageUploaderService $imageUploaderService
    ) {
        parent::__construct($context);
        $this->imageUploaderService = $imageUploaderService;
    }

    public function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Anee_Shopfinder::manage_shopfinder');
    }

    public function execute(): ResultInterface
    {
        try {
            $imageId = $this->_request->getParam('param_name', 'image');
            $result = $this->imageUploaderService->saveFileToTmpDir($imageId);
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
