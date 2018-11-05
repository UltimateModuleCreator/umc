/*
 *
 * UMC
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @copyright Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru <ultimate.module.creator@gmail.com>
 *
 */

function wrapFormElements(elements) {
    for(i=0; i < elements.length; i+=2){
        elements.slice(i, i+2).wrapAll('<div class="row"/>');
    }
}

function detectPlacement(tooltip, element) {
    return ($(element).parent().prev().length === 0) ? 'left' : 'right';
}
function triggerTooltips(element) {
    $(element).find('[data-toggle="tooltip"]').tooltip({'placement': detectPlacement});
}

jQuery.widget('umc.umcmodule', {
    options: {
        entityTemplateSelector : '',
        attributeTemplateSelector: '',
        entityContainer: '',
        addEntityTrigger: '',
        entityTemplate: '',
        attributeTemplate: '',
        data: {},
        attrConfig: {}
    },
    _create: function () {
        var self = this;
        this.entityIndex = 0;
        $(this.options.addEntityTrigger).on('click', function () {
            self.addEntity({});
        });
        this.options.entityTemplate = $(this.options.entityTemplateSelector).html();
        this.options.attributeTemplate = $(this.options.attributeTemplateSelector).html();
        $(this.options.entityTemplateSelector).remove();
        $(this.options.attributeTemplateSelector).remove();
        wrapFormElements($('#data_module').find(' > .form-group'));
        var i;
        var data = this.options.data;
        if (data) {
            for (i in data) {
                if (data.hasOwnProperty(i)) {
                    $('#data_module_' + i).val(data[i]).trigger('change');
                }
            }
        }
        triggerTooltips(this.element);
        if (typeof data._entities !== "undefined") {
            for (i = 0; i< data._entities.length ; i++) {
                this.addEntity(data._entities[i]);
            }
        }
        $(this.element).on('submit', function(e){
            var form = $(this);
            $('#body-loader').show();
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: $(this).serialize(),
                success: function(data) {
                    var content = data.message;
                    if (typeof data.validation !== "undefined") {
                        content = '<ul>';
                        content += '<li>'+  data.validation.join('</li><li>') + '</li>';
                        content += '</ul>';
                    }
                    var modal = $('#response-modal');
                    modal.find('.modal-body').html(content);
                    modal.modal('show');
                },
                complete: function () {
                    $('#body-loader').hide();
                }
            });

            e.preventDefault()
        })
    },
    addEntity: function(data) {
        if (this.options.entityTemplate && this.options.entityContainer) {
            $(this.options.entityContainer).append(this.options.entityTemplate.replace(/__entity__/g, this.entityIndex));
            $('#entity-container-' + this.entityIndex).umcentity({
                attributeTemplate:this.options.attributeTemplate,
                index: this.entityIndex,
                data: data,
                attrConfig: this.options.attrConfig
            });
            this.entityIndex++;
        }
    }
});

jQuery.widget('umc.umcentity', {
    options: {
        attributeTemplate: '',
        attributeContainer: '.attribute-container',
        index: '',
        data: {},
        attrConfig: {}
    },
    _create: function () {
        this.attributeIndex = 0;
        var self = this;
        wrapFormElements($(this.element).find( '.entity-element > .form-group').not(':last'));
        wrapFormElements($(this.element).find( '.entity-element > .form-group:last'));
        $(this.element).find('.remove-entity').on('click', function (e) {
            e.preventDefault();
            if (confirm('Confirm entity remove!')) {
                $(self.element).remove();
            }
        });
        $(this.element).find('.add-attribute').on('click', function() {
            self.addAttribute({});
        });
        $(this.element).find('.label-singular').on('change', function() {
            var val = ($(this).val()) ? $(this).val() : '<i>Entity</i>';
            $(self.element).find('[data-umc=entity-label-title]').html(val);
        });
        var data = this.options.data;
        var i;
        if (data) {
            for (i in data) {
                if (data.hasOwnProperty(i)) {
                    $('#data_entity_' + this.options.index + '_' + i).val(data[i]).trigger('change');
                }
            }
        }
        triggerTooltips(this.element);
        if (typeof data._attributes !== "undefined") {
            for (i = 0; i< data._attributes.length ; i++) {
                this.addAttribute(data._attributes[i]);
            }
        }
    },
    addAttribute: function (data) {
        if (this.options.attributeTemplate && this.options.attributeContainer) {
            $(this.element).find(this.options.attributeContainer).append(
                this.options.attributeTemplate.replace(/__entity__/g, this.options.index).replace(/__attribute__/g, this.attributeIndex)
            );
            $('#attribute-container-' + this.options.index + '-'+this.attributeIndex).umcattribute({
                entityId: this.options.index,
                index: this.attributeIndex,
                data: data,
                attrConfig: this.options.attrConfig
            });
            this.attributeIndex++;
        }
    }
});

jQuery.widget('umc.umcattribute', {
    options: {
        entityId: '',
        index: '',
        data: {},
        attrConfig: {}
    },
    _create: function () {
        var self = this;
        wrapFormElements($(this.element).find('.attribute-element > .form-group'));
        $(this.element).find('.remove-attribute').on('click', function (e) {
            e.preventDefault();
            if (confirm('Confirm attribute remove!')) {
                $(self.element).remove();
            }
        });
        $(this.element).find('.label').on('change', function() {
            var val = ($(this).val()) ? $(this).val() : '<i>Attribute</i>';
            $(self.element).find('[data-umc=attribute-label-title]').html(val);
        });
        var isNameOption  =$(this.element).find('input.is-name');
        $(isNameOption).attr('name', 'data[entity][' + this.options.entityId + '][is_name]');
        $(isNameOption).attr('value', this.options.index);
        data = this.options.data;
        var i;
        if (data) {
            for (i in data) {
                if (i === 'is_name') {
                    continue;
                }
                if (data.hasOwnProperty(i)) {
                    $('#data_attribute_' + this.options.entityId + '_' + this.options.index + '_' + i).val(data[i]).trigger('change');
                }
            }
        }
        if (data.is_name === "1") {
            $('#data_attribute_' + this.options.entityId + '_' + this.options.index + '_is_name').prop('checked', 'checked').trigger('change');
        }
        $(this.element).find('[umc-type=type]').on('change', function () {
            var val = $(this).val();
            $(self.element).find('[umc-type=required]').prop('disabled', !self.options.attrConfig[val]['can_be_required']);
            $(self.element).find('[umc-type=is-name]').prop('disabled', !self.options.attrConfig[val]['can_be_name']);
            $(self.element).find('[umc-type=options]').prop('disabled', !self.options.attrConfig[val]['can_have_options']);
            $(self.element).find('[umc-type=admin-grid]').prop('disabled', !self.options.attrConfig[val]['can_show_in_grid']);
            $(self.element).find('[umc-type=admin-grid-filter]').prop('disabled', !self.options.attrConfig[val]['can_show_in_grid']);
        });
        $(this.element).find('[umc-type=type]').trigger('change');
        triggerTooltips(this.element);
    }
});
