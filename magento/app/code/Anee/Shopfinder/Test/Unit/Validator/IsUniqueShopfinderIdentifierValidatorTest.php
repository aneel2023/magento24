<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Test\Unit\Validator;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator;

class IsUniqueShopfinderIdentifierValidatorTest extends TestCase
{
    /** @var ShopfinderRepositoryInterface|MockObject */
    private $shopfinderRepository;

    protected function setUp(): void
    {
        $this->shopfinderRepository = $this->createMock(ShopfinderRepositoryInterface::class);

        $this->sut = new IsUniqueShopfinderIdentifierValidator(
            $this->shopfinderRepository
        );
    }

    public function testValidateSameIdentifier(): void
    {
        $identifier = 'west-store';
        $id = 1;
        $shopfinder = $this->createMock(ShopfinderInterface::class);
        $shopByIdentifier = $this->createMock(ShopfinderInterface::class);

        $this->shopfinderRepository->expects($this->once())->method('getShopByIdentifier')
            ->with($identifier)->willReturn($shopByIdentifier);

        $shopByIdentifier->expects(self::once())->method('getIdentifier')
            ->willReturn($identifier);

        $shopByIdentifier->expects(self::once())->method('getEntityId')
            ->willReturn($id);
        $shopfinder->expects(self::once())->method('getEntityId')
            ->willReturn(2);

        $this->expectException(AlreadyUsedIdentifierException::class);
        $this->expectErrorMessage(
            (string)__('A shop with identifier "%1" already exists.', $identifier)
        );

        $this->sut->validate($identifier, $shopfinder);
    }

    /**
     * @return void
     * @throws AlreadyUsedIdentifierException
     */

    public function testValidate(): void
    {
        $identifier = 'west-store';
        $id = 1;
        $shopfinder = $this->createMock(ShopfinderInterface::class);
        $shopByIdentifier = $this->createMock(ShopfinderInterface::class);

        $this->shopfinderRepository->expects($this->once())->method('getShopByIdentifier')
            ->with($identifier)->willReturn($shopByIdentifier);

        $shopByIdentifier->expects(self::once())->method('getIdentifier')
            ->willReturn($identifier);

        $shopByIdentifier->expects(self::once())->method('getEntityId')
            ->willReturn($id);
        $shopfinder->expects(self::once())->method('getEntityId')
            ->willReturn($id);

        $this->assertTrue($this->sut->validate($identifier, $shopfinder));
    }
}
