<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Test\Unit\GraphQl\Response\Provider;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\GraphQl\Response\Provider\ShopfinderResponseDataProvider;
use Magento\Store\Model\StoreManagerInterface;

class ShopfinderResponseDataProviderTest extends TestCase
{
    /** @var StoreManagerInterface|MockObject  */
    private $storeManager;
    private ShopfinderResponseDataProvider $sut;

    protected function setUp(): void
    {
        $this->storeManager = $this->createMock(StoreManagerInterface::class);

        $this->sut = new ShopfinderResponseDataProvider(
            $this->storeManager
        );
    }
    public function testGet(): void
    {
        $rawData = [
            'id' => 1,
            'name' => 'East',
            'identifier' => 'est',
            'country' => 'PK',
            'image' => '',
            'longitude' => '',
            'latitude' => '',
            'createdAt' => '2023-07-30',
            'updatedAt' => '2023-07-31',

        ];
        $shopfinder = $this->createMock(ShopfinderInterface::class);

        $shopfinder->expects(self::once())->method('getEntityId')
            ->willReturn($rawData['id']);

        $shopfinder->expects($this->once())->method('getName')
            ->willReturn($rawData['name']);

        $shopfinder->expects($this->once())->method('getIdentifier')
            ->willReturn($rawData['identifier']);

        $shopfinder->expects($this->once())->method('getCountry')
            ->willReturn($rawData['country']);

        $shopfinder->expects($this->once())->method('getImage')
            ->willReturn($rawData['image']);

        $shopfinder->expects($this->once())->method('getLongitude')
            ->willReturn($rawData['longitude']);

        $shopfinder->expects($this->once())->method('getLatitude')
            ->willReturn($rawData['latitude']);

        $shopfinder->expects($this->once())->method('getCreatedAt')
            ->willReturn($rawData['createdAt']);

        $shopfinder->expects($this->once())->method('getUpdatedAt')
            ->willReturn($rawData['updatedAt']);

        $result = $this->sut->get($shopfinder);

        $this->assertEquals($rawData, $result);
    }
}
