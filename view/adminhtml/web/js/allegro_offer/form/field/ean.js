define([
    'Magento_Ui/js/form/element/abstract',
    'Macopedia_Allegro/js/allegro_offer/validation/ean'
], function (Input) {
    return Input.extend({
        initialize: function () {
            this._super();
            this.validation = this.validation || {};
            this.validation['allegro-ean'] = true;
            this.validation['max_text_length'] = 18;
        }
    });
});
