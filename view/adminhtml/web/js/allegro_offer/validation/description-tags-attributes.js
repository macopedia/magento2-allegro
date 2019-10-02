define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {

    validator.addRule(
        'allegro-offer-description-tags-attributes',
        function (value) {
            if (/\<([^\>\s]+?)(\s+[^\>\s]+)+\>/gm.test(value)) {
                return false;
            }
            return true;
        }
        ,$.mage.__('Allegro disallows HTML tags with attributes like class, name, id etc.')
    );

});
