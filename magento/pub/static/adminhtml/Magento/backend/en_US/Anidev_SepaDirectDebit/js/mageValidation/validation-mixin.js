define(
    ['jquery', 'Magento_Ui/js/lib/validation/utils', 'Anidev_SepaDirectDebit/js/sepa-validation'],
    function ($, utils, sepaValidator) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-iban-backend',
            function (value) {
                const country = $('#order-billing_address_country_id');
                return utils.isEmptyNoTrim(value) ||
                    sepaValidator.isIbanValidForCountry(value, country.length > 0 ? country.val() : '');
            },
            $.mage.__('Wrong IBAN')
        );

        $.validator.addMethod(
            'validate-iban-frontend',
            function (value) {
                const billingForm = $('#billingAddress-checkout-form');
                const shippingForm = $('#co-shipping-form');
                const sameAsShipping = $("[name='billing-address-same-as-shipping']");
                let countryId = billingForm.length > 0 ? billingForm.find("[name='country_id']").val() : '';
                if (sameAsShipping.length > 0 && sameAsShipping.is(':checked')) {
                    countryId = shippingForm.length > 0 ? shippingForm.find("[name='country_id']").val() : '';
                }
                return utils.isEmptyNoTrim(value) ||
                    sepaValidator.isIbanValidForCountry(value, countryId);
            },
            $.mage.__('Wrong IBAN')
        );


        $.validator.addMethod(
            'validate-swift',
            function (value) {
                const iban = $('#iban');
                return utils.isEmptyNoTrim(value) ||
                    sepaValidator.isSwiftValidForCountry(value, iban.length > 0 ? iban.val() : '');
            },
            $.mage.__('Wrong BIC')
        );
    }
});
