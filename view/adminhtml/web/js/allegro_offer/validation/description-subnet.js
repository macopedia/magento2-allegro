define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {

    validator.addRule(
        'allegro-offer-description-subnet',
        function (value) {
            let regex = /(\<)([^\<]*?)\>[^\<]*\<\/([^\<]*?)\>/gm;

            while (regex.test(value)) {
                value = value.replace(regex, '');
            }

            value = value.replace(/\s*/gm, '');

            if (value.length > 0) {
                return false;
            }

            return true;
        }
        ,$.mage.__("Allegro disallows text without HTML tags.")
    );

});
