/**
 * Script d'administration pour DC Scroll Top
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialisation du color picker
        if ($.fn.wpColorPicker) {
            $('.color-field').wpColorPicker();
        }
    });

})(jQuery);