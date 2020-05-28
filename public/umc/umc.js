if (typeof UMC === "undefined") {
    UMC = {config: {}}
}
UMC.Model = function (data, configKey) {
    let self = this;
    let config = UMC.config[configKey] || {};
    let dataFields = config.fields || [];
    let children = config.children || {};
    this.data = this.data || {};
    for (let i = 0; i < dataFields.length; i++) {
        let field = dataFields[i];
        this.data[field] = ko.observable(data[field] || '');
    }

    this.visibleChildrenContainer = {};
    this.visibleChildrenContainerClass = {};
    this.children = {};
    for (let child in children) {
        if (children.hasOwnProperty(child)) {
            let childConfig = children[child];
            this.children[child] = ko.observableArray((data[child] || []).map(function (childElement) {
                return new UMC.Model(childElement, childConfig.formKey);
            }));
            this.visibleChildrenContainer[child] = ko.observable(false);
            this.visibleChildrenContainerClass[child] = ko.observable('fa fa-2x fa-chevron-up');
        }
    }
    this.collapseAllChildren = function (group) {
        self.children[group]().forEach(function (item) {
            item.panelVisible(false);
        })
    };
    this.expandAllChildren = function (group) {
        self.children[group]().forEach(function (item) {
            item.panelVisible(true);
        })
    };
    this.toggleChildContainer = function (group) {
        let value = self.visibleChildrenContainer[group]();
        self.visibleChildrenContainer[group](!value);
        self.visibleChildrenContainerClass[group]('fa fa-2x fa-chevron-' + (value ? 'up' : 'down'));
    };
    //define actions
    this.addChild = function (data, child) {
        self.children[child].push(new UMC.Model(data, config.children[child].formKey));
    };

    this.removeChild = function (obj, child) {
        if (confirm("Confirm delete for '" + obj.panelTitle() + "'")) {
            self.children[child].remove(obj);
        }
    };
    this.hasChild = function (child) {
        return self.children[child] !== undefined;
    };
    this.childrenCount = function (child) {
        return (self.hasChild(child)) ? self.children[child]().length : 0;
    }
    this.radioChange = function (element, child, field) {
        if (element.data[field]()) {
            self.children[child]().forEach(function (childElement) {
                if (childElement !== element) {
                    childElement.data[field](false);
                }
            });
            element.data[field](true);
        }
        return true;
    }
    this.uuidValue = ko.observable('');
    this.uuid = function (name) {
        if (!self.uuidValue()) {
            let uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
                let r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            });
            self.uuidValue(uuid);
        }
        return name + self.uuidValue();
    };
    //init panel settings
    this.panelVisible = ko.observable(true);
    this.collapseClass = ko.pureComputed(function () {
        return 'fa fa-2x fa-chevron-' + (self.panelVisible() ? 'up' : 'down');
    });
    this.togglePanel = function () {
        self.panelVisible(!self.panelVisible())
    };
    this.panelTitle = ko.pureComputed(function () {
        if (!config.panel) {
            return '';
        }
        let labelFields = config.panel.fields || {};
        let titleParts = [];
        for (let field in labelFields) {
            if (labelFields.hasOwnProperty(field) && self.data[field]()) {
                titleParts.push(self.data[field]());
            }
        }
        if (titleParts.length === 0) {
            return config.panel.default;
        }
        return titleParts.join(' ');
    });

    //define export
    this.toParams = function () {
        let data = {};
        for (let field in self.data) {
            if (self.data.hasOwnProperty(field)) {
                let value = (typeof self.data[field] === "function") ? self.data[field]() : self.data[field];
                data[field] = typeof value === "boolean" ? (value ? 1 : 0) : value;
            }
        }
        for (let child in self.children) {
            if (self.children.hasOwnProperty(child)) {
                data[child] = self.children[child]().map(function (instance) {
                    return instance.toParams();
                });
            }
        }
        return data;
    }
}
UMC.sorting = function () {
    return {
        init: function (element, valueAccessor) {
            var list = valueAccessor();
            $(element).sortable({
                handle: ".sort-handle",
                placeholder: 'drag-placeholder',
                update: function (event, ui) {
                    var item = ko.dataFor(ui.item[0]);
                    //figure out its new position
                    var position = ko.utils.arrayIndexOf(ui.item.parent().children(), ui.item[0]);
                    if (position >= 0) {
                        //remove the item and add it back in the right spot
                        list.remove(item);
                        //I heave no freakin' idea why the dragged element needs to be removed,
                        //but it doesn't work without it. damn javascript and its ways
                        $(ui.item[0]).remove();
                        list.splice(position, 0, item);
                    }
                }
            });
        }
    }
};

ko.bindingHandlers.sortableListEntities = UMC.sorting();
ko.bindingHandlers.sortableListAttributes = UMC.sorting();
ko.bindingHandlers.sortableListOptions = UMC.sorting();
ko.bindingHandlers.sortableListDynamic = UMC.sorting();
ko.bindingHandlers.select2 = {
    init: function (el, valueAccessor, allBindingsAccessor, viewModel) {
        ko.utils.domNodeDisposal.addDisposeCallback(el, function () {
            $(el).select2('destroy');
        });

        let allBindings = allBindingsAccessor(),
            select2 = ko.utils.unwrapObservable(allBindings.select2);

        $(el).select2(select2);
    },
    update: function (el, valueAccessor, allBindingsAccessor, viewModel) {
        let allBindings = allBindingsAccessor();

        if ("value" in allBindings) {
            if ((allBindings.select2.multiple || el.multiple) && allBindings.value().constructor !== Array) {
                $(el).val(allBindings.value().split(',')).trigger('change');
            } else {
                $(el).val(allBindings.value()).trigger('change');
            }
        } else if ("selectedOptions" in allBindings) {
            let converted = [];
            let textAccessor = function (value) {
                return value;
            };
            if ("optionsText" in allBindings) {
                textAccessor = function (value) {
                    let valueAccessor = function (item) {
                        return item;
                    }
                    if ("optionsValue" in allBindings) {
                        valueAccessor = function (item) {
                            return item[allBindings.optionsValue];
                        }
                    }
                    let items = $.grep(
                        allBindings.options(),
                        function (e) {
                            return valueAccessor(e) == value;
                        }
                    );
                    if (items.length == 0 || items.length > 1) {
                        return "UNKNOWN";
                    }
                    return items[0][allBindings.optionsText];
                }
            }
            $.each(allBindings.selectedOptions(), function (key, value) {
                converted.push({ id: value, text: textAccessor(value) });
            });
            $(el).select2(converted);
        }
        $(el).trigger("change");
    }
};


