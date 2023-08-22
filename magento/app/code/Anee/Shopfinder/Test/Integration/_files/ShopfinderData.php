<?php
declare(strict_types=1);
/**
 * Copyright © Anee. All rights reserved.
 */
use Magento\TestFramework\Helper\Bootstrap;
use Anee\Shopfinder\Api\ShopfinderRepositoryInterface;
use Anee\Shopfinder\Api\Data\ShopfinderInterface;

$objectManager = Bootstrap::getObjectManager();

/** @var ShopfinderInterface $shopfinder */
$shopfinder = $objectManager->create(ShopfinderInterface::class);
/** @var ShopfinderRepositoryInterface $shopfinderRepository */
$shopfinderRepository = $objectManager->create(ShopfinderRepositoryInterface::class);

$shopfinder->setName('East Store');
$shopfinder->setIdentifier('east - '.random_int(10, 1000));
$shopfinder->setCountry('PK');
$shopfinder->setIdentifier('');
$shopfinder = $shopfinderRepository->save($shopfinder);
return [
    'entity_id' => $shopfinder->getEntityId(),
    'identifier' => $shopfinder->getIdentifier()
];
