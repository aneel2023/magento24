<?php
declare(strict_types=1);

namespace Anee\Shopfinder\Test\Unit\Resolver;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\GraphQl\Response\Provider\ShopfinderResponseDataProvider;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Anee\Shopfinder\GraphQl\Resolver\ShopfinderResolver;

class ShopfinderResolverTest extends TestCase
{
    private MockObject|ShopfinderResponseDataProvider $shopfinderDataProvider;
    private MockObject|ShopfinderRepositoryInterface $shopfinderRepository;

    private ShopfinderResolver $sut;

    public function setUp(): void
    {
        $this->shopfinderDataProvider = $this->createMock(ShopfinderResponseDataProvider::class);
        $this->shopfinderRepository = $this->createMock(ShopfinderRepositoryInterface::class);


        $this->sut = new ShopfinderResolver(
            $this->shopfinderRepository,
            $this->shopfinderDataProvider
        );
    }

    public function testValidationFailOnIdentifier(): void
    {
        $this->expectException(GraphQlInputException::class);
        $this->expectExceptionMessage("'identifier' input argument is required.");

        $args = [];
        $values = [];

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $resolveInfo = $this->createMock(ResolveInfo::class);

        $this->sut->resolve($field, $context, $resolveInfo, $values, $args);
    }

    public function testValidationFailOnIdentifierNotExist(): void
    {
        $this->expectException(GraphQlNoSuchEntityException::class);
        $this->expectExceptionMessage('Shop does not exist with requested identifier.');

        $args = ['identifier' => '33223'];
        $values = [];

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $resolveInfo = $this->createMock(ResolveInfo::class);
        $exception = $this->createMock(NoSuchEntityException::class);

        $this->shopfinderRepository->expects($this->once())->method('getShopByIdentifier')
            ->with($args['identifier'])->willThrowException($exception);

        $this->sut->resolve($field, $context, $resolveInfo, $values, $args);
    }


    public function testResolve(): void
    {
        $args = ['identifier' => 'east'];
        $values = [];

        $field = $this->createMock(Field::class);
        $context = $this->createMock(ContextInterface::class);
        $resolveInfo = $this->createMock(ResolveInfo::class);
        $shopfinder = $this->createMock(ShopfinderInterface::class);

        $this->shopfinderRepository->expects($this->once())->method('getShopByIdentifier')
            ->with($args['identifier'])->willReturn($shopfinder);


        $this->shopfinderDataProvider->expects(self::once())->method('get')
            ->with($shopfinder)->willReturn($this->getExpectedResponse());

        $result = $this->sut->resolve($field, $context, $resolveInfo, $values, $args);
        $this->assertEquals($this->getExpectedResponse(), $result);
    }

    private function getExpectedResponse(): array
    {
        return [[
            'id' => 1,
            'identifier' => 'east',
            'name' => 'East Store',
            'country' => 'PK',
            'image' => '',
            'longitude' => null,
            'latitude' => null,
            'createdAt' => '2023-07-30',
            'updatedAt' => '2023-07-31'
        ]];
    }
}
