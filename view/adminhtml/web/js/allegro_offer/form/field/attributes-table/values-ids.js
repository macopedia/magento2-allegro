define([
    'knockout'
], function (ko) {

    return function (configuration) {

        var valuesIds = {};

        valuesIds._computedValue = function () {
            var val = valuesIds.inputValue();

            if (valuesIds.definition.restrictions.allowedNumberOfValues > 1) {
                if (val == "") {
                    return [];
                }
                return val;
            }

            return [val];
        };

        valuesIds.initializeValue = function (value) {
            if (value == undefined) {
                return;
            }
            if (valuesIds.definition.restrictions.allowedNumberOfValues > 1) {
                valuesIds.inputValue(value);
                return;
            }
            valuesIds.inputValue(value[0]);
        };

        valuesIds.template = 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/values-ids';
        valuesIds.definition = configuration.definition;
        valuesIds.table = configuration.table;
        valuesIds.inputValue = ko.observable("");
        valuesIds.value = ko.computed(valuesIds._computedValue, valuesIds);

        return valuesIds;

    };

});