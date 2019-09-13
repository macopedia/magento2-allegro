define([
    'knockout'
], function (ko) {

    return function (configuration) {

        var values = {};

        values._computedValue = function () {
            var val = values.inputValue();

            return [val];
        };

        values.initializeValue = function (value) {
            if (value == undefined) {
                return;
            }
            // TODO implement multiple values support
            values.inputValue(value[0]);
        };

        values.template = 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/values';
        values.definition = configuration.definition;
        values.table = configuration.table;
        values.inputValue = ko.observable("");
        values.value = ko.computed(values._computedValue, values);

        return values;
    };

});