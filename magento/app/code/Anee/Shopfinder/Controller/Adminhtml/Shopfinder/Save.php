<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Controller\Adminhtml\Shopfinder;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Model\Shopfinder;
use Anee\Shopfinder\Model\ShopfinderFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Anee\Shopfinder\Service\ImageUploaderService;
use Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;
use Magento\Framework\Controller\ResultInterface;
use Anee\Shopfinder\Controller\Adminhtml\Shopfinder as MainShopfinder;

class Save extends MainShopfinder implements HttpPostActionInterface
{
    public const URL_PATH = 'admin/shopfinder/shopfinder/save';
    public const REDIRECT_PATH = 'admin/shopfinder/shopfinder/index';
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /** @var ImageUploaderService  */
    private $imageUploaderService;
    /** @var ShopfinderFactory  */
    private $shopfinderFactory;

    /** @var IsUniqueShopfinderIdentifierValidator  */
    private $shopfinderIdentifierValidator;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        DataPersistorInterface $dataPersistor,
        ImageUploaderService $imageUploaderService,
        ShopfinderFactory $shopfinderFactory,
        ShopfinderRepositoryInterface $shopfinderRepository,
        IsUniqueShopfinderIdentifierValidator $shopfinderIdentifierValidator
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageUploaderService = $imageUploaderService;
        $this->shopfinderFactory = $shopfinderFactory;
        $this->shopfinderIdentifierValidator = $shopfinderIdentifierValidator;
        parent::__construct($context, $coreRegistry, $shopfinderRepository);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        print_r($data);
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            /** @var Shopfinder $model */
            $model = $this->shopfinderFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                try {
                    $model = $this->shopfinderRepository->getById((int)$id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This shop no longer exists.'));
                }
            }
            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
                $this->imageUploaderService->moveFileFromTmp($data['image']);
            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {
                $data['image'] = $data['image'][0]['name'];
            } else {
                $data['image'] = '';
            }
            $model->setData($data);

            try {
                $this->shopfinderIdentifierValidator->validate($model->getIdentifier(), $model);
                $this->shopfinderRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the shop.'));
                $this->dataPersistor->clear('shopfinder_shopfinder');
            } catch (AlreadyUsedIdentifierException $e) {
                $this->messageManager->addExceptionMessage(
                    $e, __($e->getMessage())
                );
                $this->dataPersistor->set('shopfinder_shopfinder', $data);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set('shopfinder_shopfinder', $data);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the shop.'));
                $this->dataPersistor->set('shopfinder_shopfinder', $data);
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
