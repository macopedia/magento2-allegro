define([
    'jquery',
    'underscore',
    'ko',
    'uiClass',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/validator'
], function ($, _, ko, uiClass, validator) {
    'use strict';

    return uiClass.extend({

        defaults: {
            template: "",
            definition: {},
        },

        initialize: function (definition) {
            this._super();
            this.value = ko.computed(this._computedValue, this);
            this.definition = definition;
            this.errorMessage= ko.observable("");
        },

        initializeValue: function (value) {},

        _computedValue: function () {
            return null;
        },

        validate: function () {
            this.errorMessage('');
            let result = validator.validate(this);
            if (result !== true) {
                this.errorMessage(result);
                return false;
            }
            return true;
        },

        hasRestriction: function (type) {
            var result = false;
            $.each(this.definition.restrictions, function (key, restriction) {
                if (restriction.type === type) {
                    result = true;
                    return false;
                }
            });
            return result;
        },

        getRestrictionValue: function (type) {
            var result = false;
            $.each(this.definition.restrictions, function (key, restriction) {
                if (restriction.type === type) {
                    result = restriction.value;
                    return false;
                }
            });
            return result;
        }

    });

});