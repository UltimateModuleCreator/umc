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

ko.bindingHandlers.umcinfo = {
    init: function (el, valueAccessor, allBindingsAccessor, viewModel) {
        let allBindings = allBindingsAccessor().umcinfo || {};
        $(el).on('click', function () {
            let modal = $('[role=dialog]:first');
            modal.find('.modal-title').html(allBindings.title || '')
            modal.find('.modal-body').html(allBindings.content || '')
            modal.modal('show');
        });
    }
}
