<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */

namespace Anee\Shopfinder\Validator;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;
use Magento\Framework\Exception\NoSuchEntityException;

class IsUniqueShopfinderIdentifierValidator
{
    /** @var ShopfinderRepositoryInterface */
    private $shopfinderRepository;
    public function __construct(
        ShopfinderRepositoryInterface $shopfinderRepository
    ) {
        $this->shopfinderRepository = $shopfinderRepository;
    }

    /**
     * @throws AlreadyUsedIdentifierException
     */
    public function validate(string $identifier, ShopfinderInterface $shopfinder): bool
    {
        try {
            $shopByIdentifier = $this->shopfinderRepository->getShopByIdentifier($identifier);
            if (!empty($shopByIdentifier->getIdentifier()) &&
                $shopByIdentifier->getEntityId() !== $shopfinder->getEntityId()) {
                throw new AlreadyUsedIdentifierException(
                    __('A shop with identifier "%1" already exists.', $identifier)
                );
            }
        } catch (NoSuchEntityException $exception) {
            return true;
        }

        return true;
    }
}
