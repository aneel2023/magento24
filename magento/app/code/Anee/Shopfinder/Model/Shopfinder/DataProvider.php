<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Model\Shopfinder;

use Anee\Shopfinder\Model\ResourceModel\Shopfinder\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;
use Magento\Framework\UrlInterface;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

class DataProvider extends ModifierPoolDataProvider
{
    /**
     * @var \Chaltask\Shopfinder\Model\ResourceModel\Shopfinder\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /** @var StoreManagerInterface  */
    private $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $shopfinderCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->collection = $shopfinderCollectionFactory->create();
        $this->storeManager = $storeManager;
        $this->dataPersistor = $dataPersistor;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data,
            $pool
        );
    }

    /**
     * Get data
     *
     * @return array|null
     */
    public function getData(): ?array
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Chaltask\Shopfinder\Model\Shopfinder $shopfinder */
        foreach ($items as $shopfinder) {
            $this->loadedData[$shopfinder->getId()] = $shopfinder->getData();
            if ($shopfinder->getImage()) {
                $m['image'][0]['name'] = $shopfinder->getImage();
                $m['image'][0]['url'] = $this->getMediaUrl($shopfinder->getImage());
                $fullData = $this->loadedData;
                $this->loadedData[$shopfinder->getId()] = array_merge($fullData[$shopfinder->getId()], $m);
            }
        }

        $data = $this->dataPersistor->get('shopfinder_shopfinder');
        if (!empty($data)) {
            $shopfinder = $this->collection->getNewEmptyItem();
            $shopfinder->setData($data);
            $this->loadedData[$shopfinder->getId()] = $shopfinder->getData();
            $this->dataPersistor->clear('shopfinder_shopfinder');
        }

        return $this->loadedData;
    }

    public function getMediaUrl(string $image = ''): string
    {
        try {
            $store = $this->storeManager->getStore();
            return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . ShopfinderInterface::IMAGE_DIR_PATH . $image;
        } catch (\Exception $exception) {
            return '';
        }
    }
}
