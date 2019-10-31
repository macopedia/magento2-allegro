define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {
    validator.addRule(
        'allegro-name-length',
        function (value) {
            /**
             * https://github.com/allegro/allegro-api/issues/919#issuecomment-458847412
             */
            var allegroEscapeList = {
                '&': '&amp;',
                '"': '&quot;',
                '<': '&lt;',
                '>': '&gt;'
            };

            var valueLength = value.replace(/./g, function(c) {
                return allegroEscapeList.hasOwnProperty(c) ? allegroEscapeList[c] : c
            }).length;

            if (valueLength > 50) {
                return false
            }
            return true
        },
        $.mage.__('Maximum name length cannot exceed 50 characters, but some characters are counted as longer: &, "", <, >')
    );
});
