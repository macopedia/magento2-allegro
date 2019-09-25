define([
    'jquery',
    'knockout',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/abstract-attribute',
], function ($, ko, abstract) {
    'use strict';

    return abstract.extend({

        defaults: {
            template: 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/values-ids',
        },

        initialize: function() {
            this.inputValue = ko.observable("");
            this._super();
        },

        initializeValue: function (value) {
            if (value === undefined || value === null || (Array.isArray(value) && value.length < 1)) {
                return;
            }
            if (this.hasRestriction('multipleChoices') && this.getRestrictionValue('multipleChoices')) {
                this.inputValue(value);
                return;
            }
            this.inputValue(value[0]);
        },

        _computedValue: function () {
            var val = this.inputValue();

            if (val === "" || val === undefined) {
                return [];
            }
            if (this.hasRestriction('multipleChoices') && this.getRestrictionValue('multipleChoices')) {
                return val;
            }
            return [val];
        }

    });

});