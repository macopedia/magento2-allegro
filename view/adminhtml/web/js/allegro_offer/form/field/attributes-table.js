define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract',
    'Magento_Ui/js/lib/validation/validator',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/values',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/values-ids',
    'Macopedia_Allegro/js/allegro_offer/form/field/attributes-table/range',
    'mage/translate'
], function ($, ko, abstractElement, validator, values, valuesIds, range) {
    'use strict';

    validator.addRule(
        'attributes-table-validation',
        function (value, params, additionalParams) {
            if (!additionalParams.self) {
                return false;
            }

            if (!additionalParams.self.attributes()) {
                return false;
            }

            var result = true;
            $.each(additionalParams.self.attributes(), function (id, attribute) {
                result = result && attribute.validate();
            });
            return result;
        },
        ''
    );

    return abstractElement.extend({

        defaults: {
            imports: {
                _categoryUpdatedCallback: '${ $.parentName }.category:value',
            },
        },

        loading: ko.observable(0),
        attributes: ko.observable([]),
        attributesLoaded: ko.observable(false),
        attributesByCategoryId: {},
        loadAttributesAjax: null,

        initialize: function () {
            this._super();

            this.validation = this.validation || {};
            this.validation['attributes-table-validation'] = true;

            this.validationParams = this.validationParams || {};
            this.validationParams['self'] = this;

            return this;
        },

        _initializeValue: function () {
            var self = this;

            $.each(self.attributes(), function (key, attribute) {
                attribute.initializeValue(self.value()[attribute.definition.id]);
            });

            var valueSubscribers = this.value._subscriptions;
            this.value = ko.computed(this._computedValue, this);
            this.value._subscriptions = valueSubscribers;
            this.attributesLoaded(true);
        },

        _resetValue: function () {
            this.attributes([]);
            var value = this.value(),
                valueSubscribers = this.value._subscriptions;
            this.value = ko.observable('');
            this.value._subscriptions = valueSubscribers;
            this.value(value);
            this.attributesLoaded(false);
        },

        _computedValue: function () {
            var result = {};
            $.each(this.attributes(), function (id, attribute) {
                result[attribute.definition.id] = attribute.value();
            });
            return result;
        },

        _categoryUpdatedCallback: function (category) {
            if (this.loadAttributesAjax) {
                this.loadAttributesAjax.abort();
            }
            if (!category) {
                this._resetValue();
                return;
            }
            this._loadAttributes(category);
        },

        _loadAttributes: function (category) {
            if (this.attributesByCategoryId[category]) {
                this._processResponse(this.attributesByCategoryId[category]);
                return;
            }

            var self = this;
            this.loadAttributesAjax = $.ajax({
                url: '/rest/V1/allegro/offer/get-parameters/' + category,
                method: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    self._showSpinner();
                },
                success: function (response) {
                    self.attributesByCategoryId[category] = response;
                    self._processResponse(response);
                },
                error: function (response) {
                    if (response.statusText === 'abort') {
                        return;
                    }
                    // TODO implement error popup
                    console.log('error');
                    console.log(response);
                },
                complete: function () {
                    self._hideSpinner();
                }
            });
        },

        _processResponse: function (attributesDefinition) {
            var self = this,
                attributes = [];
            $.each(attributesDefinition, function(k, attributeDefinition) {
                attributes.push(self._createAttribute(attributeDefinition));
            });

            self.attributes(attributes);

            if (!self.attributesLoaded()) {
                self._initializeValue();
            }
        },

        _createAttribute: function (attributeDefinition) {
            var types = {
                'range': range,
                'values': values,
                'values_ids': valuesIds
            };

            if (types[attributeDefinition['frontend_type']] === undefined) {
                throw 'Invalid parameter type';
            }

            return new types[attributeDefinition['frontend_type']](attributeDefinition);
        },

        _showSpinner: function () {
            this.loading(this.loading()+1);
        },

        _hideSpinner: function () {
            this.loading(this.loading()-1);
        }

    });


});
