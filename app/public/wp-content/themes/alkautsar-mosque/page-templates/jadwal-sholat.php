<?php
/**
 * Template Name: Jadwal Sholat
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Jadwal Sholat', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Jadwal sholat hari ini & kalender bulanan, berdasarkan koordinat Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">

		<section class="prayer-today-section">
			<div class="prayer-strip__head">
				<h2 class="prayer-strip__title" style="color:var(--secondary);">
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
					<?php esc_html_e( 'Jadwal Sholat Hari Ini', 'alkautsar' ); ?>
				</h2>
				<p class="prayer-strip__date" id="prayer-date-page" style="color:var(--accent-deep);"><?php echo esc_html( wp_date( 'l, j F Y' ) ); ?></p>
			</div>
			<div class="prayer-strip__grid prayer-strip__grid--page" id="prayer-grid-page" role="list">
				<div class="prayer-cell prayer-cell--loading" role="listitem">
					<span class="prayer-cell__name"><?php esc_html_e( 'Memuat jadwal…', 'alkautsar' ); ?></span>
				</div>
			</div>
			<div class="prayer-strip__next prayer-strip__next--page" id="prayer-next-page" hidden style="background: rgba(212,175,55,0.1); color: var(--secondary);">
				<span style="color:var(--secondary);"><?php esc_html_e( 'Menuju', 'alkautsar' ); ?> <strong id="prayer-next-name-page" style="color:var(--accent-deep);"></strong>:</span>
				<span class="prayer-strip__next-time" id="prayer-next-countdown-page" style="color:var(--accent-deep);"></span>
			</div>
		</section>

		<section style="margin-top:4rem;">
			<div class="section-head section-head--center">
				<p class="section-eyebrow"><?php esc_html_e( 'Kalender Bulanan', 'alkautsar' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Jadwal Sholat Sebulan', 'alkautsar' ); ?></h2>
				<p class="section-desc"><?php esc_html_e( 'Klik tanggal untuk melihat detail waktu sholat.', 'alkautsar' ); ?></p>
			</div>

			<div class="prayer-calendar-controls">
				<button type="button" id="prayer-cal-prev" class="btn btn--ghost btn--sm" aria-label="<?php esc_attr_e( 'Bulan sebelumnya', 'alkautsar' ); ?>">←</button>
				<span id="prayer-cal-month-label" class="prayer-calendar-label"><?php echo esc_html( wp_date( 'F Y' ) ); ?></span>
				<button type="button" id="prayer-cal-next" class="btn btn--ghost btn--sm" aria-label="<?php esc_attr_e( 'Bulan berikutnya', 'alkautsar' ); ?>">→</button>
				<button type="button" id="prayer-cal-today" class="btn btn--primary btn--sm"><?php esc_html_e( 'Hari Ini', 'alkautsar' ); ?></button>
			</div>

			<div class="prayer-calendar" id="prayer-calendar">
				<div class="prayer-calendar__loading"><?php esc_html_e( 'Memuat kalender sholat…', 'alkautsar' ); ?></div>
			</div>

			<div id="prayer-day-detail" class="prayer-day-detail" hidden>
				<h3 id="prayer-day-detail-title"></h3>
				<div id="prayer-day-detail-grid" class="prayer-day-detail__grid"></div>
			</div>
		</section>

		<section style="margin-top:4rem; padding:1.5rem 2rem; background: var(--base-alt); border-left: 4px solid var(--accent); border-radius: var(--radius-md); max-width:760px; margin-left:auto; margin-right:auto;">
			<h3 style="margin-top:0; font-size:1.125rem; color: var(--secondary);">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:0.5rem; color:var(--accent-deep);"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
				<?php esc_html_e( 'Tentang Jadwal Sholat', 'alkautsar' ); ?>
			</h3>
			<p style="font-size:0.9375rem; color: var(--ink-soft); margin-bottom:0.5rem;">
				<?php esc_html_e( 'Jadwal dihitung berdasarkan koordinat lokasi masjid (latitude & longitude) yang dapat diatur oleh admin melalui Customizer. Metode perhitungan default: Kemenag RI.', 'alkautsar' ); ?>
			</p>
			<p style="font-size:0.8125rem; color: var(--ink-soft); font-style:italic;">
				<?php esc_html_e( 'Sumber data: Aladhan Prayer Times API (aladhan.com). Data di-cache selama 1 minggu untuk efisiensi.', 'alkautsar' ); ?>
			</p>
		</section>

	</div>
</main>

<?php get_footer(); ?>
