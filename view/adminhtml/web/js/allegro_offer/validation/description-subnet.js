define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {

    validator.addRule(
        'allegro-offer-description-subnet',
        function (value) {
            return true;
        }
        ,$.mage.__("Allegro disallows text without HTML tags.")
    );

});