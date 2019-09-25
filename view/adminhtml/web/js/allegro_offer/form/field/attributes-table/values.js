define([
    'jquery',
    'knockout',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/abstract-attribute',
], function ($, ko, abstract) {
    'use strict';

    return abstract.extend({

        defaults: {
            template: 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/values',
        },

        initialize: function() {
            this.inputValue=  ko.observable([]);
            this._super();
        },

        initializeValue: function (value) {
            if (value === undefined || value === null || (Array.isArray(value) && value.length < 1)) {
                this.addNextValue();
                return;
            }
            var self = this;
            $.each(value, function (k, v) {
                self.addNextValue(v);
            });
        },

        _computedValue: function () {
            var result = [];
            $.each(this.inputValue(), function (index, value) {
                result.push(value());
            });
            return result;
        },

        addNextValue: function (value) {
            if (value === undefined) {
                value = "";
            }
            var inputValue = [...this.inputValue()];
            inputValue.push(ko.observable(value));
            this.inputValue(inputValue);
        },

        removeValue: function (index) {
            var inputValue = [...this.inputValue()];
            inputValue.splice(index, 1);
            this.inputValue(inputValue);
        }

    });

});