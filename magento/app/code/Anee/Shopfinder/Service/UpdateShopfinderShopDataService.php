<?php
declare(strict_types=1);

namespace Anee\Shopfinder\Service;

use Anee\Shopfinder\Api\Data\ShopfinderInterface;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Exception\AlreadyUsedIdentifierException;
use Anee\Shopfinder\Exception\UpdateShopfinderDataException;
use Anee\Shopfinder\GraphQl\Data\UpdateShopfinderShopData;
use Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator;
use Magento\Directory\Model\CountryFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

class UpdateShopfinderShopDataService
{
    public function __construct(
        private readonly ShopfinderRepositoryInterface $shopfinder,
        private readonly CountryFactory $countryFactory,
        private readonly IsUniqueShopfinderIdentifierValidator $shopfinderIdentifierValidator
    ) {
    }

    /**
     * @throws LocalizedException
     * @throws UpdateShopfinderDataException
     */
    public function execute(
        ShopfinderInterface $shopfinder,
        UpdateShopfinderShopData $data
    ): ShopfinderInterface {
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
            $this->shopfinder->save($shopfinder);
        } catch (AlreadyUsedIdentifierException $e) {
            throw new UpdateShopfinderDataException(
                __($e->getMessage()),
                $e
            );
        } catch (CouldNotSaveException $exception) {
            throw new UpdateShopfinderDataException(
                __('Shop was not updated due to error "%1".', $exception->getMessage()),
                $exception
            );
        }
        return $shopfinder;
    }
}
