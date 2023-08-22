<?php
declare(strict_types=1);

namespace Anee\Shopfinder\GraphQl\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\GraphQl\Response\Provider\ShopfinderResponseDataProvider;
use Magento\Framework\Exception\NoSuchEntityException;

class ShopfinderResolver implements ResolverInterface
{
    /** @var ShopfinderRepositoryInterface */
    private $shopfinderRepository;

    /** @var ShopfinderResponseDataProvider */
    private $shopfinderDataProvider;

    public function __construct(
        ShopfinderRepositoryInterface  $shopfinderRepository,
        ShopfinderResponseDataProvider $shopfinderDataProvider
    )
    {
        $this->shopfinderRepository = $shopfinderRepository;
        $this->shopfinderDataProvider = $shopfinderDataProvider;
    }

    /**
     * @throws GraphQlAuthenticationException
     * @throws GraphQlNoSuchEntityException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlInputException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.LongParameterList)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        if (empty($args['identifier'])) {
            throw new GraphQlInputException(
                __("'identifier' input argument is required.")
            );
        }
        try {
            $shopfinder = $this->shopfinderRepository->getShopByIdentifier($args['identifier']);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(
                __('Shop does not exist with requested identifier.'), $e
            );
        }

        return $this->shopfinderDataProvider->get($shopfinder);
    }
}
