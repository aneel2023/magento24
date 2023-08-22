<?php
declare(strict_types=1);

namespace Anee\Shopfinder\GraphQl\Resolver;

use Anee\Shopfinder\GraphQl\Data\UpdateShopfinderShopData;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthenticationException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\Context;
use Anee\Shopfinder\Exception\UpdateShopfinderDataException;
use Anee\Shopfinder\Service\UpdateShopfinderShopDataService;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\GraphQl\Response\Provider\ShopfinderResponseDataProvider;
use Exception;

class UpdateShopfinderShopResolver implements ResolverInterface
{
    /** @var ShopfinderRepositoryInterface  */
    private $shopfinderRepository;
    /** @var ShopfinderResponseDataProvider  */
    private $shopfinderResponseDataProvider;
    public function __construct(
        ShopfinderRepositoryInterface $shopfinderRepository,
        ShopfinderResponseDataProvider $shopfinderResponseDataProvider,
        UpdateShopfinderShopDataService $updateShopfinderShopDataService
    ) {
        $this->shopfinderRepository = $shopfinderRepository;
        $this->shopfinderResponseDataProvider = $shopfinderResponseDataProvider;
        $this->updateShopfinderShopDataService = $updateShopfinderShopDataService;
    }

    /**
     * @param Context $context
     *
     * @throws GraphQlInputException
     * @throws GraphQlAuthenticationException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlNoSuchEntityException
     * @throws UpdateShopfinderDataException
     * @throws LocalizedException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.LongParameterList)
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {
        if (empty($args['id']) && (empty($args['name']) || empty($args['identifier']) || empty($args['country']))) {
            throw new GraphQlInputException(
                __("'id', 'name' , 'identifier' and 'country` input arguments are required.")
            );
        }

        try {
            $shopfinder = $this->shopfinderRepository->getById((int) $args['id']);
        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Shop does not exist'), $e);
        }

        try {
            $data = new UpdateShopfinderShopData($args);
            $shopfinder = $this->updateShopfinderShopDataService->execute($shopfinder, $data);
        } catch (Exception $e) {
            throw new GraphQlAuthenticationException(__($e->getMessage()), $e);
        }

        return $this->shopfinderResponseDataProvider->get($shopfinder);
    }
}
