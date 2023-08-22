<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

class Image extends Column
{
    /** @var UrlInterface  */
    private $urlBuilder;

    /** @var StoreManagerInterface  */
    private $storeManager;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['image']) && !empty($item['image'])) {
                    $mediaRelativePath = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
                    $imagePath = $mediaRelativePath . ShopfinderInterface::IMAGE_DIR_PATH . $item['image'];
                    $item[$fieldName . '_src'] = $imagePath;
                    $item[$fieldName . '_alt'] = $fieldName;
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                        'shopfinder/shopfinder/edit',
                        ['id' => $item['entity_id']]
                    );
                    $item[$fieldName . '_orig_src'] = $imagePath;
                }
            }
        }

        return $dataSource;
    }
}
