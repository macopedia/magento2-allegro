define([
    'jquery',
    'mage/translate'
],function ($) {
    'use strict';

    return {

        validate: function (attribute) {
            switch (attribute.definition.frontend_type) {
                case 'range':
                    return this._validateAttributeRange(attribute);
                case 'values':
                    return this._validateAttributeValues(attribute);
                case 'values_ids':
                    return this._validateAttributeValuesIds(attribute);
            }
            return true;
        },

        _validateAttributeRange: function(attribute) {
            if (!this.__validateBothRangeValuesFilled(attribute._computedValue().minValue, attribute._computedValue().maxValue)) {
                return $.mage.__("Fill second value");
            }
            if (!this.__validateRequired(attribute, attribute._computedValue().minValue) || !this.__validateRequired(attribute, attribute._computedValue().maxValue)) {
                return $.mage.__("This parameter is required");
            }
            if (!this.__validateFloat(attribute, attribute._computedValue().minValue)) {
                return $.mage.__("Invalid min value format! Allowed format is decimal number");
            }
            if (!this.__validateFloat(attribute, attribute._computedValue().maxValue)) {
                return $.mage.__("Invalid max value format! Allowed format is decimal number");
            }
            if (!this.__validateRangeValues(attribute, attribute._computedValue().minValue, attribute._computedValue().maxValue)) {
                return $.mage.__("Min value can not be bigger than max value");
            }
            if (!this.__validateInteger(attribute, attribute._computedValue().minValue)) {
                return $.mage.__("Invalid min value format! Allowed format is integer"); // TODO test
            }
            if (!this.__validateInteger(attribute, attribute._computedValue().maxValue)) {
                return $.mage.__("Invalid max value format! Allowed format is integer"); // TODO test
            }
            if (!this.__validateMin(attribute, attribute._computedValue().minValue)) {
                return $.mage.__("Min value is to small! Minimal value is: %1").replace("%1", attribute.getRestrictionValue('min'));
            }
            if (!this.__validateMax(attribute, attribute._computedValue().maxValue)) {
                return $.mage.__("Max value is to big! Maximal value is: %1").replace("%1", attribute.getRestrictionValue('max'));
            }
            if (!this.__validatePrecision(attribute, attribute._computedValue().minValue)) {
                return $.mage.__("Min value is to precise! Maximal precision is: %1").replace("%1", attribute.getRestrictionValue('precision'));
            }
            if (!this.__validatePrecision(attribute, attribute._computedValue().maxValue)) {
                return $.mage.__("Max value is to precise! Maximal precision is: %1").replace("%1", attribute.getRestrictionValue('precision'));
            }

            return true;
        },

        _validateAttributeValues: function (attribute) {
            if (!this.__validateNumberOfValues(attribute)) {
                return $.mage.__("To many values! Max allowed values is: %1").replace("%1", attribute.getRestrictionValue('allowedNumberOfValues'));
            }
            if (attribute.definition.required && attribute._computedValue().length < 1) {
                return $.mage.__("This parameter is required"); // TODO Test
            }

            return this.__validateMultipleValues(attribute, function (attribute, value) {
                if (!this.__validateRequired(attribute, value)) {
                    return $.mage.__("This parameter is required"); // TODO Test
                }
                if (!this.__validateFloat(attribute, value)) {
                    return $.mage.__("Invalid value format! Allowed format is decimal number");
                }
                if (!this.__validateInteger(attribute, value)) {
                        return $.mage.__("Invalid value format! Allowed format is integer");
                }
                if (!this.__validateMin(attribute, value)) {
                    return $.mage.__("Value is to small! Minimal value is: %1").replace("%1", attribute.getRestrictionValue('min'));
                }
                if (!this.__validateMax(attribute, value)) {
                    return $.mage.__("Value is to big! Maximal value is: %1").replace("%1", attribute.getRestrictionValue('max'));
                }
                if (!this.__validatePrecision(attribute, value)) {
                    return $.mage.__("Value is to precise! Maximal precision is: %1").replace("%1", attribute.getRestrictionValue('precision'));
                }
                if (!this.__validateMinLength(attribute, value)) {
                    return $.mage.__("Value is short! Minimal value length is: %1").replace("%1", attribute.getRestrictionValue('minLength'));
                }
                if (!this.__validateMaxLength(attribute, value)) {
                    return $.mage.__("Value is to long! Maximal value length is: %1").replace("%1", attribute.getRestrictionValue('maxLength'));
                }

                return true;
            });
        },

        _validateAttributeValuesIds: function (attribute) {
            if (!this.__validateNumberOfValues(attribute)) { // TODO Is this restriction allowed for multiple select fields?
                return $.mage.__("To many values! Max allowed values is: %1").replace("%1", attribute.getRestrictionValue('allowedNumberOfValues'));
            }
            if (attribute.definition.required && attribute._computedValue().length < 1) {
                return $.mage.__("This parameter is required"); // TODO Test
            }

            return this.__validateMultipleValues(attribute, function (attribute, value) {
                if (!this.__validateRequired(attribute, value)) {
                    return $.mage.__("This parameter is required"); // TODO Test
                }
                return true;
            });
        },

        __validateMultipleValues: function(attribute, singleValueValidationCallBack) {
            var allValuesResult = true;
            var self = this;
            $.each(attribute._computedValue(), function (key, value) {
                let result = singleValueValidationCallBack.call(self, attribute, value);
                if (result !== true) {
                    allValuesResult = result;
                    return false;
                }
            });
            return allValuesResult;
        },

        __validateNumberOfValues: function (attribute) {
            return !attribute.hasRestriction('allowedNumberOfValues')
                || attribute._computedValue().length <= attribute.getRestrictionValue('allowedNumberOfValues');
        },

        __validateBothRangeValuesFilled: function (min, max) {
            return (min.length > 0 || max.length < 1)
                || (max.length > 0 || min.length < 1)
        },

        __validateRangeValues: function (attribute, min, max) {
            if (attribute.definition.type === 'float') {
                return parseFloat(min) <= parseFloat(max)
            }
            if (attribute.definition.type === 'integer') {
                return parseInt(min, 10) <= parseInt(max, 10)
            }
            return true;
        },

        __validateRequired: function (attribute, value) {
            return !attribute.definition.required
                || value.length > 0;
        },

        __validateFloat: function(attribute, value) {
            return value.length < 1
                || attribute.definition.type !== 'float'
                || /^[0-9]+(\.[0-9]+)?$/.test(value)
        },

        __validateInteger: function(attribute, value) {
            return value.length < 1
                || attribute.definition.type !== 'integer'
                || /^[0-9]+$/.test(value)
        },

        __validateMin: function(attribute, value) {
            return !attribute.hasRestriction('min')
                || value >= attribute.getRestrictionValue('min')
        },

        __validateMax: function(attribute, value) {
            return !attribute.hasRestriction('max')
                || value <= attribute.getRestrictionValue('max')
        },

        __validatePrecision: function(attribute, value) {
            if (!attribute.hasRestriction('precision') || !/\./.test(value)) {
                return true;
            }
            var parts = value.split('.');
            return parts[1] && parts[1].length <= attribute.getRestrictionValue('precision');
        },

        __validateMinLength: function(attribute, value) {
            return value.length < 1
                || !attribute.hasRestriction('minLength')
                || value.length >= attribute.getRestrictionValue('minLength')
        },

        __validateMaxLength: function(attribute, value) {
            return value.length < 1
                || !attribute.hasRestriction('maxLength')
                || value.length <= attribute.getRestrictionValue('maxLength')
        }

    };

});