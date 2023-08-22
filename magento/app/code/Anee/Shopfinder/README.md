---
title: README For Shopfinder Module

---

# Anee_Shopfinder  

## General  
The purpose of this module is to manage all store shops and find the shop by name and identifier near to customer.  

## Controller Actions

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\Index``
This controller is used to render the grid of shops.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\Save``
This controller is used to save the data after getting submitted from shop form.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\Delete``
This controller is used to delete single item of shop from grid row.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\MassDelete``
This controller is used to delete multiple items of shop from grid action.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\Edit``
This controller is used to show form for updating the data of shop.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\NewAction``
This controller is used to show form for adding the data of shop.

### ``\Anee\Shopfinder\Controller\Adminhtml\Shopfinder\Upload``
This controller is used to upload the image in directory of images from add/update form.

## Services

### `\Anee\Shopfinder\Service\ImageUploaderService`
This service is responsible to save the image in tmp images directory and then move to the original images directory.

### `\Anee\Shopfinder\Service\UpdateShopfinderShopDataService`
This service is responsible to update the shop data used in graphql mutation resolver class.

## Validators

### ``Anee\Shopfinder\Validator\IsUniqueShopfinderIdentifierValidator``
This validator will validate that same identifier is not exist. If same identifier is exist for any other shop then  
it will throw exception else return true.

## GraphQl APIs

### ``GetShopByIdentifier - \Anee\Shopfinder\GraphQl\Resolver\ShopfinderResolver``
This GraphQl Api is used to get shop data by input parameter identifier. It will validate first that identifier is  
exist in request if not exist then throw the exception. If shop not exist with identifier then it will also throw  
the exception but if exist then return data in array.

## ``GetShopByIdentifier Request``
{
    GetShopByIdentifier(identifier: "string") {
        country
        createdAt
        id
        identifier
        image
        latitude
        longitude
        name
        updatedAt
    }
}

### ``GetShopfinderShopList - \Anee\Shopfinder\GraphQl\Resolver\GetShopfinderShopListResolver``
This GraphQl Api is used to get all shops list. If data is not exist then it will throw exception with message.  
If shop data exist then it will return the list of shop.

## ``GetShopfinderShopList Request``
{
    GetShopfinderShopList {
        country
        createdAt
        id
        identifier
        image
        latitude
        longitude
        name
        updatedAt
    }
}

### ``DeleteShopByIdentifier - \Anee\Shopfinder\GraphQl\Resolver\DeleteShopfinderResolver``
This GraphQl Api is used to show message  when someone tried to delete shop by identifier. It will only show nice  
message that shop can not be deleted and also shop will not be deleted.

## ``DeleteShopByIdentifier Request``
{
    DeleteShopByIdentifier(identifier: "string") {
        identifier
        message
        status
    }
}

### ``UpdateShopfinderShop - \Anee\Shopfinder\GraphQl\Resolver\UpdateShopfinderShopResolver``
This GraphQl Api is used to update the shop data. It will take all data and update the shop.

## ``UpdateShopfinderShop Request``
mutation {
    UpdateShopfinderShop(
        id: 1
        name: "string"
        identifier: "string"
        country: "string"
        image: "string"
        longitude: "string"
        latitude: "string"
    ) {
        country
        createdAt
        id
        identifier
        image
        latitude
        longitude
        name
        updatedAt
    }
}


## Module Installation

`composer require anee/shopfinder`

If composer downloaded file successfully then run following command on magento root directory:
`php bin/magento s:up && php bin/magento setup:di:compile && php bin/magento s:s:d -f`

If composer not able to download the module from source then try following:

`composer require "anee/shopfinder ~1.0.0"`

If still facing any issue then download code from git:
`https://github.com/aneel2023/shopfinder`

Put above downloaded code in ``app/code/Anee/Shopfinder`` directory and execute following command:

`php bin/magento s:up && php bin/magento setup:di:compile && php bin/magento s:s:d -f`

## Run All PHP Unit Tests

1. Copy `dev/tests/unit/phpunit.xml.dist` to `dev/tests/unit/phpunit.xml`
2. Comment out line 60 -> `<string>allure/allure.config.php</string>`
3. Run following command from project root directory:

   `vendor/bin/phpunit -c dev/tests/unit/phpunit.xml vendor/anee/shopfinder/Test/Unit/`

## Run All PHP Integration Tests

1. Rename `dev/tests/integration/etc/install-config-mysql.php.dist` to `dev/tests/integration/etc/install-config-mysql.php`
2. Update `db-host`, `db-user`, `db-password` and `db-name` (Create new db for integration tests)
3. After creating new db, make sure to run `setup:upgrade and setup:di:coompile commands`
4. Copy `dev/tests/integration/phpunit.xml.dist` to `dev/integration/unit/phpunit.xml`
5. Execute `cd dev/tests/integration`
6. Run following command from above directory:

   `../../../vendor/bin/phpunit ../../../vendor/anee/shopfinder/Test/Integration/`