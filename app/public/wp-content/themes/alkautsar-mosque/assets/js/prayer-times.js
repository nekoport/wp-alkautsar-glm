/*!
 * Prayer Times — Al-Kautsar Mosque Theme
 *
 * Fetches today's prayer schedule from the Aladhan API (https://aladhan.com/prayer-times-api)
 * using the mosque coordinates and calculation method configured in Customizer.
 *
 * Strategy:
 *   1. Try cached schedule in localStorage (refreshes daily).
 *   2. If cache miss, call Aladhan API directly client-side.
 *   3. If direct call fails (CORS / offline), fall back to WordPress AJAX endpoint
 *      which proxies the request server-side (see functions.php → alkautsar_ajax_prayer_times).
 *
 * Also supports monthly calendar rendering on /jadwal-sholat page.
 */

(function () {
	'use strict';

	if (typeof alkautsarPrayer === 'undefined') {
		return;
	}

	var cfg = alkautsarPrayer;
	var i18n = cfg.i18n || {};
	var prayers = [
		{ key: 'Fajr',    label: i18n.fajr    || 'Subuh' },
		{ key: 'Sunrise', label: i18n.sunrise || 'Terbit' },
		{ key: 'Dhuhr',   label: i18n.dhuhr   || 'Dzuhur' },
		{ key: 'Asr',     label: i18n.asr     || 'Ashar' },
		{ key: 'Maghrib', label: i18n.maghrib || 'Maghrib' },
		{ key: 'Isha',    label: i18n.isha    || 'Isya' }
	];

	var cacheKey = 'alkautsar_prayer_' + new Date().toISOString().slice(0, 10) + '_' + cfg.lat + '_' + cfg.lng;

	function pad(n) { return n < 10 ? '0' + n : '' + n; }

	function todayDDMMYYYY() {
		var d = new Date();
		return pad(d.getDate()) + '-' + pad(d.getMonth() + 1) + '-' + d.getFullYear();
	}

	function renderTimings(timings, gridId, nextWrapId, nextNameId, nextCdId) {
		var grid = document.getElementById(gridId || 'prayer-grid');
		if (!grid) return;

		grid.innerHTML = '';
		var now = new Date();
		var nextPrayer = null;
		var nextDiff = Infinity;

		prayers.forEach(function (p) {
			var raw = (timings[p.key] || '').split(' ')[0];
			var parts = raw.split(':');
			if (parts.length < 2) return;
			var h = parseInt(parts[0], 10);
			var m = parseInt(parts[1], 10);
			var t = new Date();
			t.setHours(h, m, 0, 0);

			var diff = t - now;
			if (diff > 0 && diff < nextDiff) {
				nextDiff = diff;
				nextPrayer = { name: p.label, time: t };
			}

			var cell = document.createElement('div');
			cell.className = 'prayer-cell';
			cell.setAttribute('role', 'listitem');
			cell.innerHTML =
				'<span class="prayer-cell__name">' + escapeHtml(p.label) + '</span>' +
				'<span class="prayer-cell__time">' + escapeHtml(formatTime(h, m)) + '</span>';
			grid.appendChild(cell);
		});

		if (nextPrayer) {
			var cells = grid.querySelectorAll('.prayer-cell');
			var idx = prayers.findIndex(function (p) { return p.label === nextPrayer.name; });
			if (idx >= 0 && cells[idx]) {
				cells[idx].classList.add('is-next');
			}
			startCountdown(nextPrayer, nextWrapId, nextNameId, nextCdId);
		}
	}

	function formatTime(h, m) {
		return pad(h) + ':' + pad(m);
	}

	function escapeHtml(s) {
		return String(s)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#39;');
	}

	var countdownTimer = null;
	function startCountdown(next, wrapId, nameId, cdId) {
		var wrap = document.getElementById(wrapId || 'prayer-next');
		var nameEl = document.getElementById(nameId || 'prayer-next-name');
		var cdEl = document.getElementById(cdId || 'prayer-next-countdown');
		if (!wrap || !nameEl || !cdEl) return;

		nameEl.textContent = next.name;
		wrap.hidden = false;

		if (countdownTimer) clearInterval(countdownTimer);

		function tick() {
			var diff = next.time - new Date();
			if (diff <= 0) {
				clearInterval(countdownTimer);
				return;
			}
			var h = Math.floor(diff / 3600000);
			var m = Math.floor((diff % 3600000) / 60000);
			var s = Math.floor((diff % 60000) / 1000);
			cdEl.textContent = pad(h) + ':' + pad(m) + ':' + pad(s);
		}

		tick();
		countdownTimer = setInterval(tick, 1000);
	}

	function cacheTimings(timings) {
		try {
			localStorage.setItem(cacheKey, JSON.stringify({
				ts: Date.now(),
				data: timings
			}));
		} catch (e) { }
	}

	function getCachedTimings() {
		try {
			var raw = localStorage.getItem(cacheKey);
			if (!raw) return null;
			var obj = JSON.parse(raw);
			if (Date.now() - obj.ts > 24 * 3600 * 1000) return null;
			return obj.data;
		} catch (e) { return null; }
	}

	function fetchDirect() {
		var url = 'https://api.aladhan.com/v1/timings/' + todayDDMMYYYY() +
			'?latitude=' + encodeURIComponent(cfg.lat) +
			'&longitude=' + encodeURIComponent(cfg.lng) +
			'&method=' + encodeURIComponent(cfg.method);

		return fetch(url, { method: 'GET' })
			.then(function (r) {
				if (!r.ok) throw new Error('HTTP ' + r.status);
				return r.json();
			})
			.then(function (json) {
				if (!json || !json.data || !json.data.timings) {
					throw new Error('Malformed response');
				}
				return json.data.timings;
			});
	}

	function fetchViaAjax() {
		var body = new URLSearchParams();
		body.append('action', 'alkautsar_prayer_times');
		body.append('nonce', cfg.nonce);
		body.append('lat', cfg.lat);
		body.append('lng', cfg.lng);
		body.append('method', cfg.method);

		return fetch(cfg.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString()
		})
			.then(function (r) { return r.json(); })
			.then(function (json) {
				if (!json || !json.success || !json.data) {
					throw new Error('AJAX error');
				}
				return json.data;
			});
	}

	function showError(msg, gridId) {
		var grid = document.getElementById(gridId || 'prayer-grid');
		if (!grid) return;
		grid.innerHTML =
			'<div class="prayer-cell prayer-cell--loading" role="listitem">' +
			'<span class="prayer-cell__name">' + escapeHtml(msg || 'Jadwal tidak tersedia. Coba muat ulang halaman.') + '</span>' +
			'</div>';
	}

	// ════════ MONTHLY CALENDAR (only on /jadwal-sholat page) ════════

	var monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
		'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
	var dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

	var calCurrentDate = new Date();

	function fetchCalendar(year, month) {
		var cacheKeyCal = 'alkautsar_prayer_cal_' + year + '_' + month + '_' + cfg.lat + '_' + cfg.lng;
		try {
			var cached = localStorage.getItem(cacheKeyCal);
			if (cached) {
				var obj = JSON.parse(cached);
				if (Date.now() - obj.ts < 7 * 24 * 3600 * 1000) {
					return Promise.resolve(obj.data);
				}
			}
		} catch (e) { }

		var body = new URLSearchParams();
		body.append('action', 'alkautsar_prayer_calendar');
		body.append('nonce', cfg.nonce);
		body.append('year', year);
		body.append('month', month);
		body.append('lat', cfg.lat);
		body.append('lng', cfg.lng);
		body.append('method', cfg.method);

		return fetch(cfg.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString()
		})
			.then(function (r) { return r.json(); })
			.then(function (json) {
				if (!json || !json.success || !json.data) {
					throw new Error('AJAX error');
				}
				try {
					localStorage.setItem(cacheKeyCal, JSON.stringify({ ts: Date.now(), data: json.data }));
				} catch (e) { }
				return json.data;
			});
	}

	function renderCalendar(days) {
		var container = document.getElementById('prayer-calendar');
		if (!container) return;

		var year = calCurrentDate.getFullYear();
		var month = calCurrentDate.getMonth();
		var today = new Date();
		var isCurrentMonth = today.getFullYear() === year && today.getMonth() === month;
		var firstDay = new Date(year, month, 1).getDay();
		var daysInMonth = new Date(year, month + 1, 0).getDate();

		var html = '<div class="prayer-calendar__header">';
		dayNames.forEach(function (d) {
			html += '<div class="prayer-calendar__day-name">' + escapeHtml(d) + '</div>';
		});
		html += '</div><div class="prayer-calendar__body">';

		for (var i = 0; i < firstDay; i++) {
			html += '<div class="prayer-calendar__cell prayer-calendar__cell--empty"></div>';
		}

		var daysMap = {};
		days.forEach(function (d) { daysMap[d.day] = d; });

		for (var dayNum = 1; dayNum <= daysInMonth; dayNum++) {
			var isToday = isCurrentMonth && dayNum === today.getDate();
			var dayData = daysMap[dayNum];
			var fajr = dayData && dayData.timings && dayData.timings.Fajr ? dayData.timings.Fajr.split(' ')[0] : '--:--';
			var maghrib = dayData && dayData.timings && dayData.timings.Maghrib ? dayData.timings.Maghrib.split(' ')[0] : '--:--';

			html += '<div class="prayer-calendar__cell' + (isToday ? ' prayer-calendar__cell--today' : '') + '" data-day="' + dayNum + '">';
			html += '<span class="prayer-calendar__cell-day">' + dayNum + '</span>';
			if (dayData && dayData.hijri) {
				html += '<span class="prayer-calendar__cell-hijri">' + escapeHtml(dayData.hijri) + '</span>';
			}
			html += '<span class="prayer-calendar__cell-times"><span>S</span>' + escapeHtml(fajr) + '</span>';
			html += '<span class="prayer-calendar__cell-times"><span>M</span>' + escapeHtml(maghrib) + '</span>';
			html += '</div>';
		}

		html += '</div>';
		container.innerHTML = html;

		// Legend
		var legend = document.createElement('div');
		legend.className = 'prayer-calendar__legend';
		legend.innerHTML = '<span><strong>S</strong> = Subuh</span><span><strong>M</strong> = Maghrib</span><span class="prayer-calendar__legend-today"></span><span>Hari ini</span>';
		container.appendChild(legend);

		// Click handlers
		container.querySelectorAll('.prayer-calendar__cell[data-day]').forEach(function (cell) {
			cell.addEventListener('click', function () {
				var dayNum = parseInt(cell.getAttribute('data-day'), 10);
				showDayDetail(daysMap[dayNum], dayNum);
			});
		});
	}

	function showDayDetail(dayData, dayNum) {
		var detail = document.getElementById('prayer-day-detail');
		var title = document.getElementById('prayer-day-detail-title');
		var grid = document.getElementById('prayer-day-detail-grid');
		if (!detail || !title || !grid) return;

		var date = new Date(calCurrentDate.getFullYear(), calCurrentDate.getMonth(), dayNum);
		title.textContent = wp_date_format(date);

		if (!dayData || !dayData.timings) {
			grid.innerHTML = '<p>Data tidak tersedia.</p>';
			detail.hidden = false;
			return;
		}

		var html = '';
		prayers.forEach(function (p) {
			var time = (dayData.timings[p.key] || '').split(' ')[0];
			html += '<div class="prayer-day-detail__item"><span>' + escapeHtml(p.label) + '</span><strong>' + escapeHtml(time) + '</strong></div>';
		});
		grid.innerHTML = html;
		detail.hidden = false;
	}

	function wp_date_format(d) {
		return dayNames[d.getDay()] + ', ' + d.getDate() + ' ' + monthNames[d.getMonth()] + ' ' + d.getFullYear();
	}

	function loadCalendar() {
		var container = document.getElementById('prayer-calendar');
		if (!container) return;

		var year = calCurrentDate.getFullYear();
		var month = calCurrentDate.getMonth() + 1;
		var label = document.getElementById('prayer-cal-month-label');
		if (label) label.textContent = monthNames[calCurrentDate.getMonth()] + ' ' + year;

		container.innerHTML = '<div class="prayer-calendar__loading">Memuat kalender sholat…</div>';

		fetchCalendar(year, month)
			.then(function (days) { renderCalendar(days); })
			.catch(function () {
				container.innerHTML = '<div class="prayer-calendar__loading">Gagal memuat kalender. Coba refresh halaman.</div>';
			});
	}

	function initCalendarControls() {
		var prev = document.getElementById('prayer-cal-prev');
		var next = document.getElementById('prayer-cal-next');
		var today = document.getElementById('prayer-cal-today');
		if (prev) prev.addEventListener('click', function () {
			calCurrentDate.setMonth(calCurrentDate.getMonth() - 1);
			loadCalendar();
		});
		if (next) next.addEventListener('click', function () {
			calCurrentDate.setMonth(calCurrentDate.getMonth() + 1);
			loadCalendar();
		});
		if (today) today.addEventListener('click', function () {
			calCurrentDate = new Date();
			loadCalendar();
		});
	}

	// ════════ INIT ════════

	function init() {
		// Today's prayer times — homepage
		var cached = getCachedTimings();
		if (cached) {
			renderTimings(cached, 'prayer-grid', 'prayer-next', 'prayer-next-name', 'prayer-next-countdown');
		}

		// Always refresh from API in background
		if (document.getElementById('prayer-grid')) {
			fetchDirect()
				.then(function (timings) { cacheTimings(timings); renderTimings(timings, 'prayer-grid', 'prayer-next', 'prayer-next-name', 'prayer-next-countdown'); })
				.catch(function () {
					fetchViaAjax()
						.then(function (timings) { cacheTimings(timings); renderTimings(timings, 'prayer-grid', 'prayer-next', 'prayer-next-name', 'prayer-next-countdown'); })
						.catch(function () { showError(null, 'prayer-grid'); });
				});
		}

		// Today's prayer times — /jadwal-sholat page
		if (document.getElementById('prayer-grid-page')) {
			if (cached) {
				renderTimings(cached, 'prayer-grid-page', 'prayer-next-page', 'prayer-next-name-page', 'prayer-next-countdown-page');
			}
			fetchDirect()
				.then(function (timings) { cacheTimings(timings); renderTimings(timings, 'prayer-grid-page', 'prayer-next-page', 'prayer-next-name-page', 'prayer-next-countdown-page'); })
				.catch(function () {
					fetchViaAjax()
						.then(function (timings) { cacheTimings(timings); renderTimings(timings, 'prayer-grid-page', 'prayer-next-page', 'prayer-next-name-page', 'prayer-next-countdown-page'); })
						.catch(function () { showError(null, 'prayer-grid-page'); });
				});
		}

		// Monthly calendar
		if (document.getElementById('prayer-calendar')) {
			initCalendarControls();
			loadCalendar();
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
