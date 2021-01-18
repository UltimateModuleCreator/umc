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

$.widget('umc.dark', {
    _create: function () {
        var self = this;
        $(this.element).prop('checked', localStorage.getItem('dark-mode'));
        this.setMode();
        $(this.element).on('change', function () {
            self.setMode();
        });
    },
    setMode: function () {
        var mode = $(this.element).prop('checked');
        if (mode) {
            $('body').addClass('umc-dark');
            localStorage.setItem('dark-mode', mode);
        } else {
            $('body').removeClass('umc-dark');
            localStorage.removeItem('dark-mode');
        }
    }
});
