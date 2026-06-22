/*!
 * Main script — Al-Kautsar Mosque Theme
 * Vanilla ES5+; no dependencies.
 */

(function () {
	'use strict';

	// ─── Mobile menu toggle ────────────────────────────────────
	var nav = document.getElementById('site-navigation');
	var toggle = nav ? nav.querySelector('.menu-toggle') : null;

	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var expanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', String(!expanded));
			nav.classList.toggle('menu-open', !expanded);
			document.body.classList.toggle('menu-active', !expanded);
			document.body.style.overflow = !expanded ? 'hidden' : '';
		});

		// Close menu when clicking a link
		nav.querySelectorAll('a').forEach(function (link) {
			link.addEventListener('click', function () {
				if (window.innerWidth <= 768) {
					toggle.setAttribute('aria-expanded', 'false');
					nav.classList.remove('menu-open');
					document.body.classList.remove('menu-active');
					document.body.style.overflow = '';
				}
			});
		});

		// Close on Escape
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && nav.classList.contains('menu-open')) {
				toggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('menu-open');
				document.body.classList.remove('menu-active');
				document.body.style.overflow = '';
				toggle.focus();
			}
		});

		// Close when clicking outside
		document.addEventListener('click', function (e) {
			if (!nav.contains(e.target) && nav.classList.contains('menu-open')) {
				toggle.setAttribute('aria-expanded', 'false');
				nav.classList.remove('menu-open');
				document.body.classList.remove('menu-active');
				document.body.style.overflow = '';
			}
		});
	}

	// ─── Sticky header shadow on scroll ────────────────────────
	var header = document.getElementById('masthead');
	if (header) {
		var onScroll = function () {
			header.classList.toggle('is-scrolled', window.scrollY > 8);
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	// ─── Scroll-to-top button ──────────────────────────────────
	var scrollBtn = document.querySelector('.scroll-top');
	if (scrollBtn) {
		var toggleScrollBtn = function () {
			scrollBtn.classList.toggle('is-visible', window.scrollY > 400);
		};
		window.addEventListener('scroll', toggleScrollBtn, { passive: true });
		toggleScrollBtn();

		scrollBtn.addEventListener('click', function () {
			window.scrollTo({ top: 0, behavior: 'smooth' });
		});
	}

	// ─── Copy to clipboard for bank account number ─────────────
	document.querySelectorAll('.js-copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var value = btn.getAttribute('data-copy') || '';
			var original = btn.innerHTML;

			var done = function () {
				btn.classList.add('copied');
				btn.textContent = '✓ ' + (btn.dataset.copiedLabel || 'Tersalin');
				setTimeout(function () {
					btn.classList.remove('copied');
					btn.innerHTML = original;
				}, 2000);
			};

			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(value).then(done).catch(fallback);
			} else {
				fallback();
			}

			function fallback() {
				var ta = document.createElement('textarea');
				ta.value = value;
				ta.setAttribute('readonly', '');
				ta.style.position = 'absolute';
				ta.style.left = '-9999px';
				document.body.appendChild(ta);
				ta.select();
				try { document.execCommand('copy'); done(); } catch (e) {}
				document.body.removeChild(ta);
			}
		});
	});

	// ─── Smooth scroll for in-page anchor links ────────────────
	document.querySelectorAll('a[href^="#"]').forEach(function (link) {
		link.addEventListener('click', function (e) {
			var id = link.getAttribute('href');
			if (id === '#' || id === '#0') return;
			var target = document.querySelector(id);
			if (target) {
				e.preventDefault();
				var offset = 100; // header height
				var top = target.getBoundingClientRect().top + window.scrollY - offset;
				window.scrollTo({ top: top, behavior: 'smooth' });
			}
		});
	});

	// ─── Reveal-on-scroll animation ────────────────────────────
	if ('IntersectionObserver' in window) {
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

		document.querySelectorAll('.program-card, .news-card, .about__media, .about__content, .transparency__visual, .donation-method').forEach(function (el) {
			el.classList.add('reveal');
			observer.observe(el);
		});
	}
})();
