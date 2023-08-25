<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
namespace Anee\Shopfinder\Service;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\GraphQl\Data\UpdateShopfinderShopData;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Anee\Shopfinder\Exception\UpdateShopfinderDataException;
use Magento\Directory\Model\CountryFactory;
use Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;

class UpdateShopfinderShopDataService
{
    /** @var ShopfinderRepositoryInterface */
    private $shopfinderRepository;

    /** @var CountryFactory */
    private $countryFactory;

    /** @var IsUniqueShopfinderIdentifierValidator */
    private $shopfinderIdentifierValidator;

    public function __construct(
        ShopfinderRepositoryInterface $shopfinderRepository,
        CountryFactory $countryFactory,
        IsUniqueShopfinderIdentifierValidator $shopfinderIdentifierValidator
    ) {
        $this->shopfinderRepository = $shopfinderRepository;
        $this->countryFactory = $countryFactory;
        $this->shopfinderIdentifierValidator = $shopfinderIdentifierValidator;
    }

    /**
     * @throws LocalizedException
     * @throws UpdateShopfinderDataException
     */
    public function execute(
        ShopfinderInterface $shopfinder,
        UpdateShopfinderShopData $data
    ):

    ShopfinderInterface {
        $country = $this->countryFactory->create()->loadByCode($data->getCountry());
        if (empty($country->getName())) {
            throw new UpdateShopfinderDataException(
                __('Country "%1" is invalid', $data->getCountry())
            );
        }
        try {
            $this->shopfinderIdentifierValidator->validate($data->getIdentifier(), $shopfinder);
            $shopfinder->setIdentifier($data->getIdentifier());
            $shopfinder->setName($data->getName());
            $shopfinder->setCountry($data->getCountry());
            $shopfinder->setImage($data->getImage());
            $shopfinder->setLongitude($data->getLongitude());
            $shopfinder->setLatitude($data->getLatitude());
            $this->shopfinderRepository->save($shopfinder);
        } catch (AlreadyUsedIdentifierException $e) {
            throw new UpdateShopfinderDataException(
                __($e->getMessage())
            );
        } catch (CouldNotSaveException $exception) {
            throw new UpdateShopfinderDataException(
                __('Shop was not updated due to error "%1".', $exception->getMessage())
            );
        }
        return $shopfinder;
    }
}
