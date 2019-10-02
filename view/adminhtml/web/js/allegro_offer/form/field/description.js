define([
    'Magento_Ui/js/form/element/wysiwyg',
    'Macopedia_Allegro/js/allegro_offer/validation/description-subnet',
    'Macopedia_Allegro/js/allegro_offer/validation/description-tags',
    'Macopedia_Allegro/js/allegro_offer/validation/description-tags-attributes',
], function (wysiwyg) {

    return wysiwyg.extend({

        initialize: function () {
            this._super();

            this.validation = this.validation || {};
            this.validation['allegro-offer-description-tags'] = true;
            this.validation['allegro-offer-description-tags-attributes'] = true;
            this.validation['allegro-offer-description-subnet'] = true;
        }

    });

});
