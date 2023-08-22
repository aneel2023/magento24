<?php
declare(strict_types=1);

namespace Anee\Shopfinder\GraphQl\Response\Provider;

class ShopfinderListResponseDataProvider
{
    /** @var ShopfinderResponseDataProvider */
    private $shopfinderResponseDataProvider;

    public function __construct(
        ShopfinderResponseDataProvider $shopfinderResponseDataProvider
    ) {
        $this->shopfinderResponseDataProvider = $shopfinderResponseDataProvider;
    }

    public function get(array $shopfinderList): array
    {
        $result = [];
        foreach ($shopfinderList as $shopfinder) {
            $result[] = $this->shopfinderResponseDataProvider->get($shopfinder);
        }
        return $result;
    }
}
