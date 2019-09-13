define([
    'knockout'
], function (ko) {

    return function (configuration) {

        var range = {};

        range._computedValue = function () {
            return {from: range.inputValueMin(), to: range.inputValueMax()};
        };

        range.initializeValue = function (value) {
            if (value == undefined) {
                return;
            }
            // TODO implement range field type
        };

        range.template = 'Macopedia_Allegro/allegro_offer/form/field/attributes-table/range';
        range.definition = configuration.definition;
        range.table = configuration.table;
        range.inputValueMin = ko.observable();
        range.inputValueMax = ko.observable();
        range.value = ko.computed(range._computedValue, range);

        return range;

    };

});