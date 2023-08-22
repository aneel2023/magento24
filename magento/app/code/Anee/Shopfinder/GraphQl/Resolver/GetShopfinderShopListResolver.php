<?php
declare(strict_types=1);

namespace Anee\Shopfinder\GraphQl\Resolver;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\GraphQl\Response\Provider\ShopfinderListResponseDataProvider;

class GetShopfinderShopListResolver implements ResolverInterface
{
    /** @var ShopfinderRepositoryInterface */
    private $shopfinderRepository;
    /** @var ShopfinderListResponseDataProvider */
    private $shopfinderListResponseDataProvider;
    /** @var SearchCriteriaBuilder  */
    private $searchCriteriaBuilder;

    public function __construct(
        ShopfinderRepositoryInterface  $shopfinderRepository,
        ShopfinderListResponseDataProvider $shopfinderListResponseDataProvider,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->shopfinderRepository = $shopfinderRepository;
        $this->shopfinderListResponseDataProvider = $shopfinderListResponseDataProvider;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
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
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $shopfinderList = $this->shopfinderRepository->getList($searchCriteria);
            if ($shopfinderList->getTotalCount() === 0){
                throw new GraphQlNoSuchEntityException(
                    __('There is no shop exist.'), $e
                );
            }
        } catch (LocalizedException $e) {
            throw new GraphQlNoSuchEntityException(
                __('There is no shop exist.'), $e
            );
        }

        return $this->shopfinderListResponseDataProvider->get($shopfinderList->getItems());
    }
}
