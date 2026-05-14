/*! Enix Animation v1.0.4 — viewport engine (vanilla JS)
 *  - Bidirectional by default (Animate Once = No)
 *  - Animate Once = Yes  →  animation runs ONLY on scroll-down, exactly once.
 *    If the element is first encountered while scrolling up (or already in
 *    view on load above the current scroll position), it is shown instantly
 *    without animation and never re-armed.
 */
(function () {
	'use strict';

	if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
		return;
	}

	var prefersReduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* -------- scroll-direction tracker -------- */
	var lastScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
	var scrollDir   = 'down'; // assume down on first paint so above-the-fold animates
	window.addEventListener('scroll', function () {
		var y = window.pageYOffset || document.documentElement.scrollTop || 0;
		if (y > lastScrollY) {
			scrollDir = 'down';
		} else if (y < lastScrollY) {
			scrollDir = 'up';
		}
		lastScrollY = y;
	}, { passive: true });

	function enixApplyTransition(el) {
		var duration = parseInt(el.getAttribute('data-enix-duration'), 10) || 600;
		var delay    = parseInt(el.getAttribute('data-enix-delay'), 10) || 0;
		var easing   = el.getAttribute('data-enix-easing') || 'ease-out';
		el.style.transition = 'opacity ' + duration + 'ms ' + easing + ' ' + delay + 'ms,' +
			' transform ' + duration + 'ms ' + easing + ' ' + delay + 'ms,' +
			' filter ' + duration + 'ms ' + easing + ' ' + delay + 'ms';
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

	/* Show without animation (instant) — for once=yes encountered on scroll-up */
	function enixShowInstant(el) {
		el.style.transition = 'none';
		el.style.animation  = 'none';
		el.classList.add('enix-anim-in');
	}

	function enixHide(el) {
		el.classList.remove('enix-anim-in');
		var type = el.getAttribute('data-enix-animation');
		if (type === 'bounce-in' || type === 'bounce-up') {
			el.style.animation = 'none';
			void el.offsetWidth;
			el.style.animation = '';
		}
	}

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
					if (once) {
						// Only animate when user is scrolling DOWN.
						if (scrollDir === 'down') {
							enixShow(el);
						} else {
							// Scrolled up into it — just reveal, no animation.
							enixShowInstant(el);
						}
						observers[key].unobserve(el);
					} else {
						enixShow(el);
					}
				} else if (!once) {
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

	window.addEventListener('elementor/frontend/init', enixInit);
	if ('MutationObserver' in window) {
		new MutationObserver(function () { enixInit(); })
			.observe(document.documentElement, { childList: true, subtree: true });
	}
})();
