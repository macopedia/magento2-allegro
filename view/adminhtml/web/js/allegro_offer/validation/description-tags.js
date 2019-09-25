define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {

    validator.addRule(
        'allegro-offer-description-tags',
        function (value) {
            if (/\<(?!\/?(h1|h2|p|b|ul|ol|li)(\s+.*)?\/?\>).*?\>/gm.test(value)) {
                return false;
            }
            return true;
        }
        ,$.mage.__("Allegro allows only the use of specific HTML tags: h1, h2, p, b, ul, ol, li.")
    );

});