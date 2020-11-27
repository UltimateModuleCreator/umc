/*
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

$.widget('umc.umcedit', {
    options: {
        saveTrigger: 'button[data-role=save]',
        loader: '#body-loader',
        modal: '#response-modal',
        errorMarker: '.fa-exclamation-circle',
        data: null,
        config: {},
        defaults: {},
        localStorageKey: 'UMC'
    },
    _create: function () {
        let self = this;
        //validate the form
        $.validate({
            form: $(this.element),
            onError: function ($form) {
                $form.find('.nav-item').each(function () {
                    if ($($(this).attr('href')).find('.form-error').length) {
                        $(this).find(self.options.errorMarker).show();
                    } else {
                        $(this).find(self.options.errorMarker).hide();
                    }
                })
            },
            onSuccess: function ($form) {
                $form.find(self.options.errorMarker).hide();
            }
        });
        //ugly workaround to be able to use jquery validation and ko submit
        $(this.element).bind(
            'submit.validation',
            function (evt) {
                evt.stopImmediatePropagation();
                return false;
            }
        );
        $(this.options.saveTrigger).on('click', function (e) {
            const form = $(self.element);
            form.trigger('submit.validation');
            if (form.isValid()) {
                self.loader(true);
                $.post({
                    url: form.attr('action'),
                    data: {data: self.moduleInstance.toParams()},
                    complete: function (response) {
                        self.loader(false);
                        let isJson = (response.responseJSON !== undefined);
                        let textResponse = '';
                        if (isJson) {
                            let data = response.responseJSON;
                            if (data.success === undefined) {
                                textResponse = 'Got this answer back. I don\'t understand it<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                            } else {
                                let className = (data.success) ? 'alert-success' : 'alert-danger';
                                textResponse = '<div class="alert ' + className + '">' + data.message + '</div>';
                                if (data.link && data.module) {
                                    textResponse += '<br /><a class="btn btn-primary col-lg-12" href="' + data.link + '">Download module ' + data.module + '</a>';
                                }
                                localStorage.removeItem(self.options.localStorageKey);
                            }
                        } else {
                            textResponse = response.responseText;
                        }
                        let modal = $(self.options.modal);
                        modal.find('.modal-title').html('');
                        modal.find('.modal-body').html(textResponse);
                        modal.modal('show');
                    }
                });
            }
        });
        let moduleData = this.options.data;
        let storageData = localStorage.getItem(this.options.localStorageKey);
        if (moduleData === null && storageData !== null) {
            if (confirm("It looks like you started creating a module. Do you want to resume it?")) {
                moduleData = JSON.parse(storageData);
            } else {
                localStorage.removeItem(this.options.localStorageKey);
            }
        }
        this.moduleInstance = new UMC.Model(moduleData, 'module', this.saveToStorage.bind(this));
        ko.applyBindings(this.moduleInstance);
    },
    loader: function (show) {
        if (this.options.loader) {
            if (show) {
                $(this.options.loader).show()
            } else {
                $(this.options.loader).hide()
            }
        }
    },
    saveToStorage: function () {
        try {
            localStorage.setItem(this.options.localStorageKey, JSON.stringify(this.moduleInstance.toParams()));
        } catch (e) {
            console.log(e);
        }
    },
});
