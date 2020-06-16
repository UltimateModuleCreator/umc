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

jQuery('document').ready(function ($) {
    function hackTheHeight()
    {
        $('.main_container').css('min-height', $(window).height());
    }
    $(window).resize(function ($) {
        hackTheHeight();
    })
    hackTheHeight();
});
