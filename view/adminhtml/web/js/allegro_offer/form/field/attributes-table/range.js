define([
    'jquery',
    'knockout',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/abstract-attribute',
], function ($, ko, abstract) {
    'use strict';

    return abstract.extend({

        defaults: {
            template: 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/range',
        },

        initialize: function() {
            this.inputValueMin = ko.observable("");
            this.inputValueMax = ko.observable("");
            this._super();
        },

        _computedValue: function () {
            return {
                minValue: this.inputValueMin(),
                maxValue: this.inputValueMax()
            };
        },

        initializeValue: function (value) {
            if (value === undefined || value === null || (Array.isArray(value) && value.length < 1)) {
                return;
            }
            this.inputValueMin = value.minValue;
            this.inputValueMin = value.maxValue;
        }

    });

});