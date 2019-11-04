define([
    'Magento_Ui/js/form/element/abstract',
    'Macopedia_Allegro/js/allegro_offer/validation/name-length'
], function (Input) {
    return Input.extend({
        allegroNameLength: function (value) {
            /**
             * https://github.com/allegro/allegro-api/issues/919#issuecomment-458847412
             */
            var allegroEscapeList = {
                '&': '&amp;',
                '"': '&quot;',
                '<': '&lt;',
                '>': '&gt;'
            };
            return value.replace(/./g, function(c) {
                return allegroEscapeList.hasOwnProperty(c) ? allegroEscapeList[c] : c
            }).length
        }
    });
});
