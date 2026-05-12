/*! Enix Animation — bidirectional viewport engine (vanilla JS) */
(function () {
	'use strict';

	if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
		return;
	}

	var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function enixApplyTransition(el) {
		var duration = parseInt(el.getAttribute('data-enix-duration'), 10) || 600;
		var delay    = parseInt(el.getAttribute('data-enix-delay'), 10) || 0;
		var easing   = el.getAttribute('data-enix-easing') || 'ease-out';
		// transition covers all transform/opacity/filter properties
		el.style.transition = 'opacity ' + duration + 'ms ' + easing + ' ' + delay + 'ms,' +
			' transform ' + duration + 'ms ' + easing + ' ' + delay + 'ms,' +
			' filter ' + duration + 'ms ' + easing + ' ' + delay + 'ms';
		// For bounce keyframe-driven animations, sync animation-duration/delay/easing too
		var type = el.getAttribute('data-enix-animation');
		if (type === 'bounce-in' || type === 'bounce-up') {
			el.style.animationDuration = duration + 'ms';
			el.style.animationDelay    = delay + 'ms';
			el.style.animationTimingFunction = easing;
		}
	}

	function enixShow(el) {
		enixApplyTransition(el);
		el.classList.add('enix-anim-in');
	}

	function enixHide(el) {
		// Re-arm so the next entry plays the animation again
		el.classList.remove('enix-anim-in');
		// Clear keyframe animation so it can replay
		var type = el.getAttribute('data-enix-animation');
		if (type === 'bounce-in' || type === 'bounce-up') {
			el.style.animation = 'none';
			// force reflow then unset so it can run again
			void el.offsetWidth;
			el.style.animation = '';
		}
	}

	// Group elements by offset so we use a unique observer per rootMargin.
	var observers = {};

	function enixGetObserver(offset) {
		var key = String(offset);
		if (observers[key]) return observers[key];

		var rootMargin = '0px 0px -' + offset + 'px 0px';

		observers[key] = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				var el   = entry.target;
				var once = el.getAttribute('data-enix-once') === 'yes';

				if (entry.isIntersecting) {
					enixShow(el);
					if (once) {
						observers[key].unobserve(el);
					}
				} else if (!once) {
					// Bidirectional: hide & re-arm when it leaves the viewport
					enixHide(el);
				}
			});
		}, {
			root: null,
			rootMargin: rootMargin,
			threshold: 0.01
		});

		return observers[key];
	}

	function enixInit() {
		var nodes = document.querySelectorAll('[data-enix-animation]:not([data-enix-bound])');
		for (var i = 0; i < nodes.length; i++) {
			var el = nodes[i];
			el.setAttribute('data-enix-bound', '1');

			if (prefersReduced) {
				el.classList.add('enix-anim-in');
				continue;
			}

			var offset = parseInt(el.getAttribute('data-enix-offset'), 10);
			if (isNaN(offset) || offset < 0) offset = 80;

			enixGetObserver(offset).observe(el);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', enixInit);
	} else {
		enixInit();
	}

	// Re-scan on Elementor frontend init (editor preview & lazy content)
	window.addEventListener('elementor/frontend/init', enixInit);
	// Catch dynamically added content
	if ('MutationObserver' in window) {
		new MutationObserver(function () { enixInit(); })
			.observe(document.documentElement, { childList: true, subtree: true });
	}
})();
