<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\GraphQl\Response\Provider;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Exception;

class ShopfinderResponseDataProvider
{
    /** @var StoreManagerInterface */
    private $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
    }

    public function get(ShopfinderInterface $shopfinder): array
    {
        $result = [
            'id' => (int)$shopfinder->getEntityId(),
            'name' => $shopfinder->getName(),
            'identifier' => $shopfinder->getIdentifier(),
            'country' => $shopfinder->getCountry(),
            'image' => $this->getImageUrl($shopfinder->getImage()),
            'longitude' => $shopfinder->getLongitude(),
            'latitude' => $shopfinder->getLatitude(),
            'createdAt' => $shopfinder->getCreatedAt(),
            'updatedAt' => $shopfinder->getUpdatedAt(),
        ];
        return $result;
    }

    private function getImageUrl(?string $image): string
    {
        if (empty($image)) {
            return '';
        }
        try {
            $store = $this->storeManager->getStore();
            return $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . ShopfinderInterface::IMAGE_DIR_PATH . $image;
        } catch (Exception $exception) {
            return $image;
        }
    }
}
