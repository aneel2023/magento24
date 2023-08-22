<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Test\Unit\Service;

use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;
use Anee\Shopfinder\Exception\UpdateShopfinderDataException;
use Anee\Shopfinder\GraphQl\Data\UpdateShopfinderShopData;
use Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator;
use Magento\Directory\Model\Country;
use Magento\Store\Api\StoreRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Service\UpdateShopfinderShopDataService;
use Magento\Directory\Model\CountryFactory;

class UpdateShopfinderShopDataServiceTest extends TestCase
{
    /** @var IsUniqueShopfinderIdentifierValidator|MockObject */
    private $shopfinderIdentifierValidator;
    /** @var StoreRepositoryInterface|MockObject  */
    private $countryFactory;
    /** @var ShopfinderRepositoryInterface|MockObject  */
    private $shopfinderRepository;
    private UpdateShopfinderShopDataService $sut;

    protected function setUp(): void
    {
        $this->shopfinderIdentifierValidator = $this->createMock(
            IsUniqueShopfinderIdentifierValidator::class
        );
        $this->countryFactory = $this->createMock(CountryFactory::class);
        $this->shopfinderRepository = $this->createMock(ShopfinderRepositoryInterface::class);

        $this->sut = new UpdateShopfinderShopDataService(
            $this->shopfinderRepository,
            $this->countryFactory,
            $this->shopfinderIdentifierValidator
        );
    }
    public function testExecute(): void
    {
        $rawData = [
            'entity_id' => 1,
            'identifier' => 'est',
            'name' => 'East',
            'country' => 'PK'

        ];
        $shopfinder = $this->createMock(ShopfinderInterface::class);
        $data = new UpdateShopfinderShopData($rawData);
        $countryModel = $this->createMock(Country::class);

        $this->countryFactory->expects($this->once())->method('create')
            ->willReturn($countryModel);
        $countryModel->expects($this->once())->method('loadByCode')
            ->with($data->getCountry())->willReturnSelf();

        $countryModel->expects(self::once())->method('getName')
            ->willReturn('Pakistan');

        $this->shopfinderIdentifierValidator->expects($this->once())->method('validate')
            ->with($data->getIdentifier(), $shopfinder)->willReturn(true);

        $this->shopfinderRepository->expects($this::once())->method('save')
            ->with($shopfinder)
            ->willReturn($shopfinder);

        $result = $this->sut->execute($shopfinder, $data);

        $this->assertInstanceOf(ShopfinderInterface::class, $result);
    }

    public function testExecuteInvalidCountry(): void
    {
        $rawData = [
            'entity_id' => 1,
            'identifier' => 'est',
            'name' => 'East',
            'country' => 'PKR'

        ];

        $shopfinder = $this->createMock(ShopfinderInterface::class);
        $data = new UpdateShopfinderShopData($rawData);
        $countryModel = $this->createMock(Country::class);

        $this->countryFactory->expects($this->once())->method('create')
            ->willReturn($countryModel);
        $countryModel->expects($this->once())->method('loadByCode')
            ->with($data->getCountry())->willReturnSelf();

        $countryModel->expects(self::once())->method('getName')
            ->willReturn(null);

        $this->expectException(UpdateShopfinderDataException::class);
        $this->sut->execute($shopfinder, $data);

    }

    public function testExecuteAlreadyExistIdentifier(): void
    {
        $rawData = [
            'entity_id' => 1,
            'identifier' => 'est',
            'name' => 'East',
            'country' => 'PK'

        ];
        $shopfinder = $this->createMock(ShopfinderInterface::class);
        $data = new UpdateShopfinderShopData($rawData);
        $countryModel = $this->createMock(Country::class);
        $exception = $this->createMock(AlreadyUsedIdentifierException::class);

        $this->countryFactory->expects($this->once())->method('create')
            ->willReturn($countryModel);
        $countryModel->expects($this->once())->method('loadByCode')
            ->with($data->getCountry())->willReturnSelf();

        $countryModel->expects(self::once())->method('getName')
            ->willReturn('Pakistan');

        $this->shopfinderIdentifierValidator->expects($this->once())->method('validate')
            ->with($data->getIdentifier(), $shopfinder)
            ->willThrowException($exception);

        $this->expectException(UpdateShopfinderDataException::class);
        $this->sut->execute($shopfinder, $data);
    }
}
