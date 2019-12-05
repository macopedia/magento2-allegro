define([
    'Magento_Ui/js/lib/validation/validator',
    'jquery',
    'mage/translate'
], function (validator, $) {
    validator.addRule(
        'allegro-ean',
        function (value) {
            if (/[^0-9]/g.test(value)) {
                return false;
            }
            let tab = value.strip("");
            let sum = 0;
            for (let i = tab.length-2; i >= 0; i -= 2) {
                sum = sum + parseInt(tab[i], 10) * 3;
                if (i >= 1) {
                    sum = sum + parseInt(tab[i-1], 10);
                }
            }
            return ((10 - (sum % 10)) === parseInt(tab[tab.length-1],10));
        },
        $.mage.__('This is not a valid EAN number')
    );
});
