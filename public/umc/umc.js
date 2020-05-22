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
        let labelField = config.panel.field || '';
        if (!labelField) {
            return config.panel.default;
        }
        let label = self.data[labelField]();
        return label ? label : config.panel.default;
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
// UMC.initModel = function (model, data, config) {
//     const dataFields = config.fields;
//     const children = config.children || {};
//     //init observables
//     model.data = model.data || {};
//     for (let i = 0; i < dataFields.length; i++) {
//         const field = dataFields[i];
//         model.data[field] = ko.observable(data[field] || '');
//     }
//     if (config.children !== undefined && Object.entries(config.children).length !== 0) {
//         //init child elements
//         model.visibleChildrenContainer = {};
//         model.visibleChildrenContainerClass = {};
//         model.children = {};
//         for (let child in children) {
//             if (children.hasOwnProperty(child)) {
//                 let className = children[child];
//                 model.children[child] = ko.observableArray((data[child] || []).map(function (childElement) {
//                     return new UMC[className](childElement);
//                 }));
//                 model.visibleChildrenContainer[child] = ko.observable(false);
//                 model.visibleChildrenContainerClass[child] = ko.observable('fa fa-2x fa-chevron-up');
//             }
//         }
//         model.collapseAllChildren = function (group) {
//             model.children[group]().forEach(function (item) {
//                 item.panelVisible(false);
//             })
//         };
//         model.expandAllChildren = function (group) {
//             model.children[group]().forEach(function (item) {
//                 item.panelVisible(true);
//             })
//         };
//         model.toggleChildContainer = function (group) {
//             let value = model.visibleChildrenContainer[group]();
//             model.visibleChildrenContainer[group](!value);
//             model.visibleChildrenContainerClass[group]('fa fa-2x fa-chevron-' + (value ? 'up' : 'down'));
//         };
//         //define actions
//         model.addChild = function (data, child) {
//             const className = config.children[child];
//             model.children[child].push(new UMC[className](data));
//         };
//
//         model.removeChild = function (obj, child) {
//             if (confirm("Confirm delete for '" + obj.panelTitle() + "'")) {
//                 model.children[child].remove(obj);
//             }
//         };
//         model.hasChild = function (child) {
//             return model.children[child] !== undefined;
//         };
//         model.radioChange = function (element, child, field) {
//             if (element.data[field]()) {
//                 model.children[child]().forEach(function (childElement) {
//                     if (childElement !== element) {
//                         childElement.data[field](false);
//                     }
//                 });
//                 element.data[field](true);
//             }
//             return true;
//         }
//     }
//     //init unique id
//     model.uuidValue = ko.observable('');
//     model.uuid = function (name) {
//         if (!model.uuidValue()) {
//             var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
//                 var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
//                 return v.toString(16);
//             });
//             model.uuidValue(uuid);
//         }
//         return name + model.uuidValue();
//     };
//     //init panel settings
//     model.panelVisible = ko.observable(true);
//     model.collapseClass = ko.pureComputed(function () {
//         return 'fa fa-2x fa-chevron-' + (model.panelVisible() ? 'up' : 'down');
//     });
//     model.togglePanel = function () {
//         model.panelVisible(!model.panelVisible())
//     };
//     model.panelTitle = ko.pureComputed(function ($params) {
//         var labelField = config.panel.field || '';
//         if (!labelField) {
//             return config.panel.default;
//         }
//         var label = model.data[labelField]();
//         return label ? label : config.panel.default;
//     });
//
//     //define export
//     model.toParams = function () {
//         var data = {};
//         for (var field in model.data) {
//             if (model.data.hasOwnProperty(field)) {
//                 var value = (typeof model.data[field] === "function") ? model.data[field]() : model.data[field];
//                 data[field] = typeof value === "boolean" ? (value ? 1 : 0) : value;
//             }
//         }
//         for (var child in model.children) {
//             if (model.children.hasOwnProperty(child)) {
//                 data[child] = model.children[child]().map(function (instance) {
//                     return instance.toParams();
//                 });
//             }
//         }
//         return data;
//     }
// };
// UMC.Module = function (data) {
//     UMC.initModel(this, data, UMC.config.module || {});
// };
// UMC.Entity = function (data) {
//     UMC.initModel(this, data, UMC.config.entity || {});
// };
// UMC.Attribute = function (data) {
//     UMC.initModel(this, data, UMC.config.attribute);
//     // this.hasOptions = ko.pureComputed(function () {
//     //      return self.isDropdown() || self.isMultiselect();
//     // });
//     // this.hasDefaultValue = ko.pureComputed(function () {
//     //     var type = self.data.type();
//     //     return !self.hasOptions() && type !== "image" && type !== "file" && type !== 'dynamic'
//     // });
//     // this.canShowInAdmin = ko.pureComputed(function () {
//     //     return self.data.type() !== 'file' && this.data.type() !== "wysiwyg" && this.data.type() !== 'dynamic';
//     // }, this);
//     // this.canHideInAdmin = ko.pureComputed(function () {
//     //     return self.data.admin_grid() && this.canShowInAdmin();
//     // }, this);
//     // this.hasExpanded = ko.pureComputed(function () {
//     //     return self.data.type() === 'dynamic';
//     // }, this);
//     // this.canBeFullText = ko.pureComputed(function () {
//     //     return self.data.type() === 'text' || self.data.type() === 'textarea' || self.data.type() === 'wysiwyg'
//     //         || self.data.type() === 'country'  || self.data.type() === 'country_multiselect';
//     // }, this);
//     // this.canBeRequired = ko.pureComputed(function () {
//     //     return self.data.type() !== 'image' && self.data.type() !== 'file';
//     // }, this);
//     // this.canFilterInAdmin = ko.pureComputed(function () {
//     //     return self.data.admin_grid() && self.canShowInAdmin()
//     //         && self.data.type() !== "image" && self.data.type() !== "product_attribute_multiselect"
//     //         && self.data.type() !== "multiselect";
//     // }, this);
// };
// UMC.Option = function (data) {
//     var self = this;
//     UMC.initModel(this, data, UMC.config.option);
// };
//
// UMC.Dynamic = function (data) {
//     var self = this;
//     UMC.initModel(this, data, UMC.config.dynamic || {});
//     // this.isDropdown = ko.pureComputed(function () {
//     //     return self.data.type() === 'dropdown';
//     // });
//     // this.isMultiselect = ko.pureComputed(function () {
//     //     return self.data.type() === 'multiselect';
//     // });
//     // this.hasOptions = ko.pureComputed(function () {
//     //     return self.isDropdown() || self.isMultiselect();
//     // });
//     this.hasDefaultValue = ko.pureComputed(function () {
//         var type = self.data.type();
//         return !self.hasOptions() && type !== "image" && type !== "file"
//     });
// };

UMC.getSortingConfig = function (placeholder) {
    return {
        init: function (element, valueAccessor) {
            var list = valueAccessor();
            $(element).sortable({
                handle: ".sort-handle",
                placeholder: placeholder,
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

ko.bindingHandlers.sortableListEntities = UMC.getSortingConfig('drag-placeholder');
ko.bindingHandlers.sortableListAttributes = UMC.getSortingConfig('drag-placeholder');
ko.bindingHandlers.sortableListOptions = UMC.getSortingConfig('drag-placeholder');
ko.bindingHandlers.sortableListDynamic = UMC.getSortingConfig('drag-placeholder');
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


