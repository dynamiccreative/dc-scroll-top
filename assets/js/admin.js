/**
 * Script d'administration pour DC Scroll Top
 */
(function($) {
    'use strict';

    $(document).ready(function() {

        // Color picker avec callback de preview
        if ($.fn.wpColorPicker) {
            $('.color-field').wpColorPicker({
                change: function(event, ui) {
                    setTimeout(updatePreview, 50);
                },
                clear: function() {
                    setTimeout(updatePreview, 50);
                }
            });
        }

        // Toggle activation
        $('#dc-stt-active').on('change', function() {
            $('#dc-stt-toggle-text').text(this.checked ? 'Active' : 'Desactive');
        });

        // Preview live
        var $preview = $('#dc-stt-preview-btn');
        var $zone    = $('#dc-stt-preview-zone');

        function getSvgDataUrl(color, stylePath) {
            var encoded = color.replace('#', '%23');
            return "url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path fill=\"" + encoded + "\" d=\"" + stylePath + "\"/></svg>')";
        }

        function updatePreview() {
            var color     = $('input[name="color"]').val() || '#000000';
            var size      = parseInt($('input[name="size"]').val(), 10) || 40;
            var posBottom = parseInt($('input[name="pos_bottom"]').val(), 10) || 10;
            var posRight  = parseInt($('input[name="pos_right"]').val(), 10) || 10;
            var styleId   = $('input[name="style"]:checked').val() || '1';
            var svgPath   = DC_STT.svg_styles[styleId] || DC_STT.svg_styles['1'];

            // Scale for preview zone (200px height)
            var scale  = 200 / 400;
            var pxSize = Math.round(size * scale);
            var pxBot  = Math.round(posBottom * scale);
            var pxR    = Math.round(posRight * scale);

            $preview.css({
                width:              pxSize + 'px',
                height:             pxSize + 'px',
                bottom:             pxBot + 'px',
                right:              pxR + 'px',
                backgroundImage:    getSvgDataUrl(color, svgPath),
                backgroundSize:     'contain',
                backgroundRepeat:   'no-repeat'
            });
        }

        // Bind preview updates
        $('input[name="size"], input[name="pos_bottom"], input[name="pos_right"]').on('input change', updatePreview);
        $('input[name="style"]').on('change', updatePreview);

        // Initial render
        if ($preview.length) {
            updatePreview();
        }
    });

})(jQuery);
