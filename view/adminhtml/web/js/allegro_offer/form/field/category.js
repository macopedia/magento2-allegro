define([
    'jquery',
    'ko',
    'Magento_Ui/js/form/element/abstract',
], function ($, ko, abstractElement) {

    return abstractElement.extend({

        rootCategories: ko.observableArray([]),
        categories: ko.observable({}),
        valuesStack: ko.observable([]),
        loading: ko.observable(false),
        ready: ko.observable(false),

        initialize: function () {
            this._super();
            this._addStackValueItem();
            this._loadRootCategories();
            this._initializeValue();
        },

        _initializeValue: function () {
            var valueSubscribers = this.value._subscriptions;
            this.value = ko.computed(this._computedValue, this);
            this.value._subscriptions = valueSubscribers;

            if (!this.initialValue) {
                this.ready(true);
                return;
            }

            var self = this;
            $.ajax({
                url: '/rest/V1/allegro/categories/all-parents/' + this.initialValue,
                method: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    self._showSpinner();
                },
                success: function (response) {
                    $.each(response, function (k, category) {
                        self._scheduleValueStackUpdate(category.id);
                    });
                },
                error: function (response) {
                    if (response.statusText === 'abort') {
                        return;
                    }
                    // TODO implement error popup
                    console.log('error 2');
                    console.log(response);
                },
                complete: function () {
                    self._hideSpinner();
                }
            });
        },

        _scheduleValueStackUpdate: function (newValue) {
            if (!this.categories()[newValue]) {
                var self = this;
                setTimeout(function () {
                    self._scheduleValueStackUpdate(newValue);
                }, 100);
                return;
            }

            var length = Object.keys(this.valuesStack()).length;
            this.valuesStack()[length-1](newValue);

            if (!this.categories()[newValue].leaf) {
                return;
            }

            this.ready(true);
        },

        _computedValue: function () {

            if (!this.ready()) {
                return this.initialValue;
            }

            var last = this._getLastValueInValuesStack();
            if (last === undefined) {
                return '';
            }

            if (!this.categories()[last]) {
                return;
            }

            if (!this.categories()[last].leaf) {
                return '';
            }

            return last;
        },

        _stackItemUpdatedCallback: function (newValue) {
            var newValueStack = [],
                skipNext = false;

            $.each(this.valuesStack(), function (key, value) {
                if (skipNext) return;
                newValueStack.push(value);
                if (newValue === value()) skipNext = true;
            });
            this.valuesStack(newValueStack);

            var last = this._getLastValueInValuesStack();

            if (last === undefined) {
                return;
            }

            if (this.categories()[last].leaf) {
                return;
            }

            this._addStackValueItem();
            this._loadCategories(newValue);
        },

        _getLastValueInValuesStack: function () {
            var length = Object.keys(this.valuesStack()).length;
            if (length < 1) {
                return;
            }
            return this.valuesStack()[Object.keys(this.valuesStack()).length-1]();
        },

        _addStackValueItem: function () {
            var item = ko.observable();
            item.subscribe(this._stackItemUpdatedCallback.bind(this));
            var stack = [...this.valuesStack()];
            stack.push(item);
            this.valuesStack(stack);
        },

        _loadCategories: function (categoryId) {
            var self = this;

            if (this.categories()[categoryId] && this.categories()[categoryId].children) {
                return;
            }

            $.ajax({
                url: '/rest/V1/allegro/categories/list/' + categoryId,
                method: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    self._showSpinner();
                },
                success: function (response) {
                    self._addCategories(response, categoryId)
                },
                error: function (response) {
                    if (response.statusText === 'abort') {
                        return;
                    }
                    // TODO implement error popup
                    console.log('error 3');
                    console.log(response);
                },
                complete: function () {
                    self._hideSpinner();
                }
            });
        },

        _loadRootCategories: function () {
            var self = this;
            $.ajax({
                url: '/rest/V1/allegro/categories/root-list/',
                method: 'GET',
                dataType: 'json',
                beforeSend: function () {
                    self._showSpinner();
                },
                success: function (response) {
                    self._addCategories(response, false);
                    $.each(response, function (k, category) {
                        self.rootCategories.push(category.id);
                    });
                },
                error: function (response) {
                    if (response.statusText === 'abort') {
                        return;
                    }
                    // TODO implement error popup
                    console.log('error 4');
                    console.log(response);
                },
                complete: function () {
                    self._hideSpinner();
                }
            });
        },

        _addCategories: function (data, parentId) {
            var categories = {...this.categories()};
            if (parentId) {
                categories[parentId].children = [];
            }
            $.each(data, function (k, category) {
                categories[category.id] = {
                    name: category.name,
                    leaf: category.leaf
                };
                if (parentId) {
                    categories[parentId].children.push(category.id);
                }
            });

            this.categories(categories);
        },

        _getCategoryName: function (categoryId) {
            return this.categories()[categoryId].name;
        },

        _showSpinner: function () {
            this.loading(this.loading()+1);
        },

        _hideSpinner: function () {
            this.loading(this.loading()-1);
        }

    });

});