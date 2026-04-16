/**
 * DC Scroll Top — vanilla JS (remplace jquery.scrollUp.js)
 */
(function() {
    'use strict';

    function init() {
        var cfg = window.DC_STT_Front || {};
        var distance  = cfg.scrollDistance || 300;
        var speed     = cfg.scrollSpeed || 300;
        var animation = cfg.animation || 'fade';
        var title     = cfg.scrollTitle || '';

        // Create button
        var btn = document.createElement('a');
        btn.id = 'scrollUp';
        btn.href = '#top';
        btn.setAttribute('role', 'button');
        btn.setAttribute('aria-label', title || 'Retour en haut');
        if (title) btn.title = title;
        document.body.appendChild(btn);

        // Add animation class
        document.body.classList.add('scrollup-' + animation);

        // Scroll listener with throttle
        var visible = false;
        var ticking = false;

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    var scrolled = window.pageYOffset || document.documentElement.scrollTop;
                    if (scrolled > distance) {
                        if (!visible) {
                            visible = true;
                            document.body.classList.add('scrollup');
                        }
                    } else {
                        if (visible) {
                            visible = false;
                            document.body.classList.remove('scrollup');
                        }
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Smooth scroll to top
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: speed <= 0 ? 'auto' : 'smooth' });
        });
    }

    // Wait for DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
