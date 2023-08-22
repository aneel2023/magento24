<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Test\Integration\Repository;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

class ShopfinderRepositoryTest extends TestCase
{
    private static ?int $entityId = 0;
    private static ?string $identifier = '';

    /** @var SearchCriteriaBuilder  */
    private $searchCriteriaBuilder;

    /** @var ShopfinderRepositoryInterface  */
    private $shopfinderRepository;

    protected function setUp(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->shopfinderRepository = $objectManager->create(
            ShopfinderRepositoryInterface::class
        );
        $this->searchCriteriaBuilder = $objectManager->create(
            SearchCriteriaBuilder::class
        );
    }

    public static function loadFixture(): void
    {
        $shopData = include __DIR__ . '/../_files/ShopfinderData.php';
        self::$entityId = (int)$shopData['entity_id'];
        self::$identifier = (string)$shopData['identifier'];
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testShopfinderPersistence(): void
    {
        /** @var ShopfinderInterface $shopData */
        $shopData = $this->shopfinderRepository->getById((int)self::$entityId);

        $this->assertEquals(self::$entityId, $shopData->getEntityId());
        $this->assertEquals(self::$identifier, $shopData->getIdentifier());
        $this->assertEquals('East Store', $shopData->getName());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testShopfinderUpdatePersistence(): void
    {
        $updatedName = 'West Store';
        /** @var ShopfinderInterface $shopData */
        $shopData = $this->shopfinderRepository->getById((int)self::$entityId);
        $shopData->setName($updatedName);
        $this->shopfinderRepository->save($shopData);
        $shopData = $this->shopfinderRepository->getById((int)self::$entityId);
        $this->assertEquals(self::$entityId, $shopData->getEntityId());
        $this->assertEquals($updatedName, $shopData->getName());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testShopfinderDeleteById(): void
    {
        $this->expectException(NoSuchEntityException::class);
        $result = $this->shopfinderRepository->deleteById((int)self::$entityId);
        $this->assertTrue($result);
        $this->shopfinderRepository->getById((int)self::$entityId);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testShopfinderGetById(): void
    {
        $this->expectException(NoSuchEntityException::class);
        $this->shopfinderRepository->getById(2);
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testGetListMethod(): void
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $items = $this->shopfinderRepository->getList($searchCriteria)->getItems();
        /** @var ShopfinderInterface $item */
        $item = reset($items);
        $this->assertCount(1, $items);
        $this->assertEquals((int)self::$entityId, $item->getEntityId());
    }

    /**
     * @magentoDbIsolation enabled
     * @magentoDataFixture loadFixture
     */
    public function testGetByIdentifier(): void
    {
        $shop = $this->shopfinderRepository->getShopByIdentifier(self::$identifier);
        $this->assertEquals((int)self::$entityId, $shop->getEntityId());
    }
}
