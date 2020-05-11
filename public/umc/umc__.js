jQuery.widget('umc.umc', {
    options: {
        moduleForm: $('form[name=umc]'),
        entityForm: $('form[name=entity]'),
        entityPopup: $('#entity-modal'),
        addEntityTrigger: $('[data-role=add-entity]'),
        saveEntityTrigger: $('[data-role=save-entity]'),
        entityHtmlTemplate: $('#entity-short').html(),
        attributePopup: $('#attribute-modal'),
        attributeForm: $('form[name=attribute]'),
        addAttributeTrigger: '[data-role=add-attribute]',
        saveAttributeTrigger: $('[data-role=save-attribute]'),
        attributeHtmlTemplate: $('#attribute-short').html(),
        moduleSaveTrigger: $('[data-role=save]'),
        loader: $('#body-loader'),
        responseModal: $('#response-modal'),
        attrConfig: {},
        defaults: {},
        data: {},
        submitAction: '',
        reservedKeywords: ['__halt_compiler', 'abstract', 'and', 'array',
            'as', 'break', 'callable', 'case',
            'catch', 'class', 'clone', 'const',
            'continue', 'declare', 'default', 'die',
            'do', 'echo', 'else', 'elseif',
            'empty', 'enddeclare', 'endfor', 'endforeach',
            'endif', 'endswitch', 'endwhile', 'eval',
            'exit', 'extends', 'final', 'for',
            'foreach', 'function', 'global', 'goto',
            'if', 'implements', 'include', 'include_once',
            'instanceof', 'insteadof', 'interface', 'isset',
            'list', 'namespace', 'new', 'or',
            'print', 'private', 'protected', 'public',
            'require', 'require_once', 'return', 'static',
            'switch', 'throw', 'trait', 'try',
            'unset', 'use', 'var', 'while', 'xor', 'void'],
        restrictedModuleNames: ['Magento'],
        restrictedEntityNames: ['resource', 'result', 'setup', 'attribute', 'options', 'system', 'data', 'collection', 'adminhtml', 'url', 'config'],
        restrictedAttributeCodes: ['identities', 'namespace', 'default_values', 'created_at', 'updated_at', 'data']
    },
    _create() {
        var self = this;
        this.canAddToLocalStorage = false;
        this.storage = {
            _entities: {},
            module: {}
        };
        this.entityIndex = 0;
        this.attributeIndex = 0;
        var moduleElements = $(this.options.moduleForm).find('select,input,textarea');
        for (var i = 0; i < moduleElements.length; i++) {
            var name = $(moduleElements[i]).attr('name');
            if (typeof this.options.data.module !== "undefined" && typeof this.options.data.module[name] !== "undefined") {
                $(moduleElements[i]).val(this.options.data.module[name]);
            } else {
                $(moduleElements[i]).val('');
            }
            $(moduleElements[i]).on('change', function() {
                self.registerModule();
            });
        }
        this.registerModule();
        this.options.moduleForm.find('.fa-chevron-up').on('click', function () {
            self.toggle(this);
        });
        if (typeof this.options.data._entities !== "undefined") {
            for (var i = 0 in this.options.data._entities) {
                if (this.options.data._entities.hasOwnProperty(i)) {
                    var entityData = this.options.data._entities[i];
                    var attributes = entityData._attributes;
                    delete entityData._attributes;
                    entityData.id = this.entityIndex;
                    this.registerEntity(entityData.id, entityData);
                    this.addUiEntity(
                        '[data-entity-id=' + entityData.id + ']',
                        '[data-role=entity-container]',
                        this.getEntityHtml(entityData)
                    );
                    this.registerAttributes(entityData.id, attributes, true);
                    this.entityIndex++;
                }
            }
        }
        $(this.options.moduleForm).find('[data-pretty-select=true]').select2({ width: '100%' });
        $(this.options.attributeForm).find('[data-pretty-select=true]').select2({ width: '100%', dropdownParent: $(this.options.attributeForm) });
        $(this.options.addEntityTrigger).on('click', function () {
            self.showEntityPopup(self.getDefaults('entity'), 'Add New Entity');
        });
        $(this.options.moduleForm).find('[data-toggle=tooltip]').tooltip();
        $(this.options.moduleForm).find('.select2-container').tooltip({
            title: function () {
                return $(this).prev().attr("data-original-title")
            }
        });
        $(this.options.entityForm).find('.select2-container').tooltip({
            title: function () {
                return $(this).prev().attr("data-original-title")
            },
            placement: 'left'
        });
        $(this.options.attributeForm).find('.select2-container').tooltip({
            title: function () {
                return $(this).prev().attr("data-original-title")
            },
            placement: 'left'
        });
        $(self.options.attributePopup).find('[umc-type=type]').on('change', function () {
            var val = $(this).val();
            $(self.options.attributePopup).find('[umc-type=required]').prop('disabled', !self.options.attrConfig[val]['can_be_required']);
            $(self.options.attributePopup).find('[umc-type=is-name]').prop('disabled', !self.options.attrConfig[val]['can_be_name']);
            $(self.options.attributePopup).find('[umc-type=options]').prop('disabled', !self.options.attrConfig[val]['can_have_options']);
            $(self.options.attributePopup).find('[umc-type=admin-grid]').prop('disabled', !self.options.attrConfig[val]['can_show_in_grid']);
            $(self.options.attributePopup).find('[umc-type=admin-grid-filter]').prop('disabled', !self.options.attrConfig[val]['can_show_in_grid']);
        });
        $(this.element).find('[umc-type=type]').trigger('change');
        this.wrapFormElements(this.options.moduleForm.find('.form-group'));
        this.wrapFormElements(this.options.entityForm.find('.form-group'));
        $(this.options.entityForm).find('[data-toggle=tooltip]').tooltip({
            placement: 'top'
        });
        this.wrapFormElements(this.options.attributeForm.find('.form-group'));
        $(this.options.attributeForm).find('[data-toggle=tooltip]').tooltip({placement:'top'});
        $(this.options.saveEntityTrigger).on('click', function () {
            if (self.options.entityForm.isValid()) {
                var entityData = self.getFormData($(self.options.entityForm));
                var currentEntityId = self.options.entityPopup.attr('data-current-entity-id');
                if (currentEntityId === '') {
                    currentEntityId = self.entityIndex;
                    self.entityIndex++;
                }
                entityData['id'] = currentEntityId;
                if (self.validateEntityData(entityData)) {
                    self.registerEntity(currentEntityId, entityData);
                    self.addUiEntity(
                        '[data-entity-id=' + currentEntityId + ']',
                        '[data-role=entity-container]',
                        self.getEntityHtml(entityData)
                    );
                    self.options.entityPopup.modal('hide');
                }
                var attributes = (typeof self.storage._entities[currentEntityId]) ? self.storage._entities[currentEntityId]._attributes : [];
                self.registerAttributes(currentEntityId, attributes, false);
            }
        });
        $(this.options.saveAttributeTrigger).on('click', function () {
            if (self.options.attributeForm.isValid()) {
                var attributeData = self.getFormData($(self.options.attributeForm));
                var currentEntityId = self.options.attributePopup.attr('data-current-entity-id');
                var currentAttributeId = self.options.attributePopup.attr('data-current-attribute-id');
                if (currentAttributeId === '') {
                    currentAttributeId = self.attributeIndex;
                    self.attributeIndex++;
                }
                attributeData['id'] = currentAttributeId;
                attributeData['entityId'] = currentEntityId;
                if (self.validateAttributeData(attributeData)) {
                    self.registerAttribute(attributeData);
                    self.addUiAttribute(
                        '[data-attribute-id=' + currentAttributeId + ']',
                        '[data-role=attribute-container-' + currentEntityId + ']',
                        self.getAttributeHtml(attributeData)
                    );
                    self.options.attributePopup.modal('hide');
                }
                if ($(this).attr('data-add-new')) {
                    var entityId = currentEntityId;
                    var title = "Add New Attribute for entity " + self.storage._entities[entityId].label_singular;
                    self.options.attributePopup.attr('data-current-entity-id', entityId);
                    self.options.attributePopup.attr('data-current-attribute-id', '');
                    self.showAttributePopup(self.getDefaults('attribute'), title);
                }
            }
        });
        $(this.options.moduleSaveTrigger).on('click', function(){
            self.saveModule();
        });
        this.canAddToLocalStorage = true;
    },
    registerAttributes: function(entityId, attributes, increment) {
        for (var j = 0 in attributes) {
            if (attributes.hasOwnProperty(j)) {
                var attribute = attributes[j];
                attribute.entityId = entityId;
                if (increment) {
                    attribute.id = this.attributeIndex;
                }
                this.registerAttribute(attribute);
                this.addUiAttribute(
                    '[data-attribute-id=' + attribute.id + ']',
                    '[data-role=attribute-container-' + entityId + ']',
                    this.getAttributeHtml(attribute)
                );
                if (increment) {
                    this.attributeIndex++;
                }
            }
        }
    },
    validateEntityData: function(data) {
        var errors = [];
        if ($.inArray(data.name_singular.toLowerCase(), this.options.reservedKeywords) !== -1
            || $.inArray(data.name_singular.toLowerCase(), this.options.restrictedEntityNames) !== -1) {
            errors.push('"' + data.name_singular +'"  cannot be used as an entity name');
        }
        if (data.name_singular.toLowerCase() === data.name_plural.toLowerCase()) {
            errors.push('Name singular and plural must be different');
        }
        for (var i in this.storage._entities) {
            if (this.storage._entities.hasOwnProperty(i)) {
                var entity = this.storage._entities[i];
                if (entity.name_singular.toLowerCase() === data.name_singular.toLowerCase() && parseInt(entity.id) !== parseInt(data.id)) {
                    errors.push("There is already an entity with the name " + data.name_singular);
                }
            }
        }
        if (errors.length > 0) {
            alert(errors.join("\n"));
            return false;
        }
        return true;
    },
    validateAttributeData: function(data) {
        var errors = [];
        var entity = this.storage._entities[data['entityId']];
        var entityAttributes = entity._attributes;
        var isName = data.is_name === "1";
        if ($.inArray(data.code.toLowerCase(), this.options.reservedKeywords) !== -1 || $.inArray(data.code.toLowerCase(), this.options.restrictedAttributeCodes) !== -1) {
            errors.push('"' + data.code +'"  cannot be used as an attribute code');
        }
        for (var i in entityAttributes) {
            if (entityAttributes.hasOwnProperty(i)) {
                var attribute = entityAttributes[i];
                if (attribute.code.toLowerCase() === data.code.toLowerCase() && parseInt(attribute.id) !== parseInt(data.id)) {
                    errors.push("There is already an attribute with the code " + data.code + ' for entity ' + entity.label_singular );
                }
                if (isName && attribute.is_name === "1" && parseInt(attribute.id) !== parseInt(data.id)) {
                    errors.push("There is already an attribute that behaves as name for entity: " + entity.label_singular + ". It is " + attribute.label);
                }
            }
        }
        if (errors.length > 0) {
            alert(errors.join("\n"));
            return false;
        }
        return true;
    },
    toggle: function(trigger) {
        $(trigger).closest('.panel').find(' > .panel-body').toggle();
        $(trigger).toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    },
    wrapFormElements: function(elements) {
        for(i=0; i < elements.length; i+=2){
            elements.slice(i, i+2).wrapAll('<div class="row"/>');
        }
        for(i=0; i < elements.length; i++){
            $(elements[i]).addClass('col-lg-6');
        }
    },
    getDefaults: function(key) {
        if (typeof this.options.defaults[key] !== "undefined" ) {
            return this.options.defaults[key];
        }
        return {};
    },
    registerEntity: function(id, data) {
        if (typeof this.storage._entities[id] !== "undefined") {
            data._attributes = this.storage._entities[id]._attributes;
        } else {
            data._attributes = {};
        }
        this.storage._entities[id] = data;
        this.setDataToLocalStorage();
    },
    registerAttribute: function(data) {
        this.storage._entities[data.entityId]._attributes[data.id] = data;
        this.setDataToLocalStorage();
    },
    registerModule: function() {
        this.storage.module = this.getFormData($(this.options.moduleForm));
        this.setDataToLocalStorage();
    },
    setDataToLocalStorage: function() {
        if (this.canAddToLocalStorage) {
            localStorage.setItem("umc", JSON.stringify(this.storage));
        }
    },
    getEntityHtml(data) {
        return this.options.entityHtmlTemplate
            .replace(/__title__/g, data.label_singular)
            .replace(/__id__/g, data.id);
    },
    getAttributeHtml(data) {
        var title = data.label + ' (' + data.code + ')' + ((data.required === "1") ? ' *' : '');
        return this.options.attributeHtmlTemplate
            .replace(/__title__/g, title)
            .replace(/__id__/g, data.id)
            .replace(/__entity_id__/g, data.entityId);
    },
    showPopup: function (id, data, title) {
        $(id).find('.modal-title').html('<h3>' + title + '</h3>');
        $(id).find('input, select, textarea').each(function () {
            $(this).val((typeof data[$(this).attr('name')] !== "undefined") ? data[$(this).attr('name')] : '');
        });
        $(id).modal({
            backdrop: 'static',
        });
        $(id).modal('show');
    },

    showEntityPopup: function(data, title) {
        if (typeof data.id === "undefined") {
            $(this.options.entityPopup).attr('data-current-entity-id', '');
        }
        this.showPopup(this.options.entityPopup, data, title);
    },
    showAttributePopup: function(data, title) {
        this.showPopup(this.options.attributePopup, data, title);
        $(this.options.attributePopup).find('[umc-type=type]').trigger('change');
        var entityId = this.options.attributePopup.attr('data-current-entity-id');
        var entity = this.storage._entities[entityId];
        $(this.options.attributePopup).find('[umc-type=frontend-view]').prop('disabled', !parseInt(entity.frontend_view));
        $(this.options.attributePopup).find('[umc-type=frontend-list]').prop('disabled', !parseInt(entity.frontend_list));
    },
    addUiElement(selector, container, element) {
        var self = this;
        var existingElement = $(selector);
        if (existingElement.length) {
            existingElement.replaceWith(element);
        } else {
            $(container).append(element);
        }
        $(selector).find('.fa-chevron-up:first').on('click', function () {
            self.toggle(this);
        });
    },
    addUiEntity(selector, container, element) {
        var self = this;
        this.addUiElement(selector, container, element);
        $(selector).find('.fa-trash').on('click', function () {
            self.deleteEntity($(selector));
        });
        $(selector).find('.fa-edit').on('click', function () {
            self.editEntity($(selector));
        });
        $(selector).find(this.options.addAttributeTrigger).on('click', function () {
            var entityId = $(selector).attr('data-entity-id');
            var title = "Add New Attribute for entity " + self.storage._entities[entityId].label_singular;
            self.options.attributePopup.attr('data-current-entity-id', entityId);
            self.options.attributePopup.attr('data-current-attribute-id', '');
            self.showAttributePopup(self.getDefaults('attribute'), title);
        });
        this.initEntityDnd();
        this.sortEntities();
    },
    addUiAttribute(selector, container, element) {
        var self = this;
        this.addUiElement(selector, container, element);
        $(selector).find('.fa-trash').on('click', function () {
            self.deleteAttribute($(selector));
        });
        $(selector).find('.fa-edit').on('click', function () {
            self.editAttribute($(selector));
        });
        this.initDnd(container);
        this.sortAttributes(container);
    },
    getFormData: function($form) {
        var serializedData = $form.serializeArray();
        var objectData = {};
        $.map(serializedData, function(n, i){
            objectData[n['name']] = n['value'];
        });
        return objectData;
    },
    deleteEntity: function (container) {
        var id = $(container).attr('data-entity-id');
        if (confirm('Confirm entity delete!')) {
            $(container).slideUp(600, function () {
                $(this).remove()
            });
            delete this.storage._entities[id];
        }
    },
    editEntity: function (container) {
        var id = $(container).attr('data-entity-id');
        this.options.entityPopup.attr('data-current-entity-id', id);
        var data = this.storage._entities[id];
        var title = data.label_singular;
        $(this.options.entityPopup).attr('data-current-entity-id', id);
        this.showEntityPopup(data, 'Edit Entity ' + title);
    },
    deleteAttribute: function (container) {
        var entityId = $(container).attr('data-entity-id');
        var id = $(container).attr('data-attribute-id');
        if (confirm('Confirm attribute delete!')) {
            $(container).slideUp(600, function () {
                $(this).remove()
            });
            delete this.storage._entities[entityId]._attributes[id];
        }
    },
    initDnd: function (container) {
        var self = this;
        $(container).sortable({
            handle: ".dnd-link, h3",
            placeholder: "drag-placeholder",
            forcePlaceholderSize: true,
            update: function (event, ui) {
                self.sortAttributes(container);
            }
        });
    },

    initEntityDnd: function() {
        var self = this;
        $('[data-role=entity-container]').sortable({
            handle: ".entity-dnd-link, h3",
            placeholder: "drag-placeholder",
            forcePlaceholderSize: true,
            update: function (event, ui) {
                self.sortEntities();
            }
        });
    },

    sortAttributes: function (container) {
        var self = this;
        var entityId = $(container).closest('[data-role=entity-panel]').attr('data-entity-id');
        var elements = $(container).children();
        var position = 10;
        for (i = 0; i<elements.length; i++) {
            var attributeId = $(elements[i]).attr('data-attribute-id');
            self.storage._entities[entityId]._attributes[attributeId].position = position;
            position += 10;
        }
    },
    sortEntities: function () {
        var self = this;
        var elements = $('[data-role=entity-container]').children();
        var position = 10;
        for (i = 0; i<elements.length; i++) {
            var entityId = $(elements[i]).attr('data-entity-id');
            self.storage._entities[entityId].position = position;
            position += 10;
        }
    },
    editAttribute: function (container) {
        var entityId = $(container).attr('data-entity-id');
        var id = $(container).attr('data-attribute-id');
        this.options.attributePopup.attr('data-current-entity-id', entityId);
        this.options.attributePopup.attr('data-current-attribute-id', id);
        var entityData = this.storage._entities[entityId];
        var data = entityData._attributes[id];
        var title = 'Edit Attribute ' + data.label + ' for entity ' + entityData.label_singular;
        $(this.options.attributePopup).attr('data-current-attribute-id', id).attr('data-current-entity-id', entityId);
        this.showAttributePopup(data, title);
    },
    validateModule: function() {
        if (!$(this.options.moduleForm).isValid()) {
            return false;
        }
        var validEntities = false;
        var errors = [];
        for (key in this.storage._entities) {
            if (this.storage._entities.hasOwnProperty(key)) {
                validEntities = true;
                var entity = this.storage._entities[key];
                var validAttributes = false;
                var validNameAttribute = false;
                for (attributeKey in entity._attributes) {
                    if (entity._attributes.hasOwnProperty(attributeKey)) {
                        validAttributes = true;
                        if (entity._attributes[attributeKey].is_name === "1") {
                            validNameAttribute = true;
                        }
                    }
                }
                if (!validAttributes) {
                    errors.push("Entity " + entity.label_singular + " does not have attributes");
                }
                if (!validNameAttribute) {
                    errors.push("Entity " + entity.label_singular + "  must have an attribute marked as 'Name'");
                }
            }
        }
        if (!validEntities) {
            errors.push("Add at least one entity");
        }
        if (errors.length) {
            alert(errors.join("\n"));
            return false;
        }
        return true;
    },
    showLoader: function() {
        $(this.options.loader).show();
    },
    hideLoader: function() {
        $(this.options.loader).hide();
    },
    saveModule: function () {
        var self = this;
        if (this.validateModule()) {
            this.showLoader();
            $.ajax({
                type: 'POST',
                url: this.options.submitAction,
                data: {data: this.storage},
                success: function (data) {
                    var className = (data.success) ? 'alert-success' : 'alert-error';
                    var content = '<div class="alert ' + className + '">' + data.message + '</div>';
                    if (data.link && data.module) {
                        content += '<br /><a class="btn btn-primary col-lg-12" href="' + data.link + '">Download module ' + data.module + '</a>';
                        $('[data-role=umc-title]').html('Edit: ' + data.module);
                    }
                    var modal = $(self.options.responseModal);
                    modal.find('.modal-body').html(content);
                    modal.modal('show');
                    delete localStorage.umc;
                },
                complete: function () {
                    self.hideLoader();
                }
            });
        }
    }
});
