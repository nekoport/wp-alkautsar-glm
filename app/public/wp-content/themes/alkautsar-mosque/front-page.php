<?php
/**
 * Front page template — Al-Kautsar Mosque homepage.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

get_header();
?>

<main id="primary" class="site-main">

        <!-- ═══════════════ HERO ═══════════════ -->
        <section class="hero" id="home">
                <div class="hero__overlay"></div>
                <div class="hero__pattern" aria-hidden="true"></div>
                <div class="container hero__content">
                        <p class="hero__arabic"><?php echo esc_html( get_theme_mod( 'alkautsar_hero_arabic', 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ' ) ); ?></p>
                        <h1 class="hero__title"><?php echo esc_html( get_theme_mod( 'alkautsar_hero_title', 'Selamat Datang di Masjid Al-Kautsar' ) ); ?></h1>
                        <p class="hero__subtitle"><?php echo esc_html( get_theme_mod( 'alkautsar_hero_subtitle', 'Rumah ibadah, pusat dakwah, dan taman kebaikan bagi umat.' ) ); ?></p>
                        <div class="hero__actions">
                                <a href="<?php echo esc_url( home_url( '/donasi' ) ); ?>" class="btn btn--gold btn--lg">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                        <?php esc_html_e( 'Donasi Sekarang', 'alkautsar' ); ?>
                                </a>
                                <a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="btn btn--outline btn--lg"><?php esc_html_e( 'Pelajari Lebih Lanjut', 'alkautsar' ); ?></a>
                        </div>
                </div>
                <div class="hero__scroll" aria-hidden="true">
                        <span></span>
                </div>
        </section>

        <!-- ═══════════════ PRAYER SCHEDULE STRIP ═══════════════ -->
        <section class="prayer-strip" aria-label="<?php esc_attr_e( 'Jadwal Sholat Hari Ini', 'alkautsar' ); ?>">
                <div class="container">
                        <div class="prayer-strip__head">
                                <h2 class="prayer-strip__title">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <?php esc_html_e( 'Jadwal Sholat Hari Ini', 'alkautsar' ); ?>
                                </h2>
                                <p class="prayer-strip__date" id="prayer-date"><?php echo esc_html( wp_date( 'l, j F Y' ) ); ?></p>
                        </div>
                        <div class="prayer-strip__grid" id="prayer-grid" role="list">
                                <!-- Filled by prayer-times.js -->
                                <div class="prayer-cell prayer-cell--loading" role="listitem">
                                        <span class="prayer-cell__name"><?php esc_html_e( 'Memuat jadwal…', 'alkautsar' ); ?></span>
                                </div>
                        </div>
                        <div class="prayer-strip__next" id="prayer-next" hidden>
                                <span class="prayer-strip__next-label"><?php esc_html_e( 'Menuju', 'alkautsar' ); ?> <strong id="prayer-next-name"></strong>:</span>
                                <span class="prayer-strip__next-time" id="prayer-next-countdown"></span>
                        </div>
                </div>
        </section>

        <!-- ═══════════════ ABOUT / PROFILE ═══════════════ -->
        <section class="about" id="about">
                <div class="container about__inner">
                        <div class="about__media">
						<?php
						$about_image = get_theme_mod( 'alkautsar_hero_image' );
						if ( $about_image ) :
							?>
							<div class="about__media-frame">
								<img src="<?php echo esc_url( $about_image ); ?>" alt="<?php esc_attr_e( 'Masjid Al-Kautsar', 'alkautsar' ); ?>" loading="lazy">
							</div>
							<div class="about__media-badge">
								<span class="about__media-badge-number"><?php echo esc_html( get_theme_mod( 'alkautsar_about_badge_number', '12+' ) ); ?></span>
								<span class="about__media-badge-label"><?php echo esc_html( get_theme_mod( 'alkautsar_about_badge_label', __( 'Tahun Mengabdi', 'alkautsar' ) ) ); ?></span>
							</div>
							<?php
						endif;
						?>
					</div>
                        <div class="about__content">
                                <p class="section-eyebrow"><?php echo esc_html( get_theme_mod( 'alkautsar_about_eyebrow', __( 'Tentang Kami', 'alkautsar' ) ) ); ?></p>
                                <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'alkautsar_about_title', __( 'Masjid Al-Kautsar — Baitullah yang Memuliakan Umat', 'alkautsar' ) ) ); ?></h2>
                                <p class="about__lead"><?php echo esc_html( get_theme_mod( 'alkautsar_about_lead', __( 'Masjid Al-Kautsar hadir sebagai pusat peribadatan, pendidikan, sosial, dan dakwah yang menebar rahmat bagi seluruh umat. Kami berkomitmen menjadikan rumah Allah ini sebagai tempat tumbuhnya keimanan, ilmu, dan ukhuwah.', 'alkautsar' ) ) ); ?></p>
                                <p class="about__text"><?php echo esc_html( get_theme_mod( 'alkautsar_about_text', __( 'Dengan arsitektur yang megah dan nuansa kekhusyukan, masjid kami menyelenggarakan berbagai kegiatan rutin: kajian pekanan, sekolah mengaji bagi anak-anak, layanan jemput zakat, hingga program sosial untuk dhuafa. Setiap inisiatif ditujukan untuk memperkuat ukhuwah islamiyah dan menebar manfaat di tengah masyarakat.', 'alkautsar' ) ) ); ?></p>
                                <ul class="about__list">
						<?php
						$about_list_raw = get_theme_mod( 'alkautsar_about_list', "Kajian Islam rutin setiap pekan
Taman pendidikan Al-Qur'an anak
Layanan sosial & santunan dhuafa
Pelaporan keuangan transparan" );
						$about_items = array_filter( array_map( 'trim', explode( "
", $about_list_raw ) ) );
						foreach ( $about_items as $item ) :
							?>
							<li>
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
								<?php echo esc_html( $item ); ?>
							</li>
						<?php endforeach; ?>
					</ul>
                                <a href="<?php echo esc_url( home_url( '/profil' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Selengkapnya', 'alkautsar' ); ?></a>
                        </div>
                </div>
        </section>

        <!-- ═══════════════ PROGRAMS ═══════════════ -->
	<section class="programs" id="program">
		<div class="container">
			<div class="section-head section-head--center">
				<p class="section-eyebrow"><?php esc_html_e( 'Program Unggulan', 'alkautsar' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Program yang Bermanfaat bagi Umat', 'alkautsar' ); ?></h2>
				<p class="section-desc"><?php esc_html_e( 'Beragam program yang dirancang untuk memperkuat iman, ilmu, dan ukhuwah di antara jamaah.', 'alkautsar' ); ?></p>
			</div>
			<div class="programs__grid">
				<?php
				$home_programs = new WP_Query( array(
					'post_type'      => 'program',
					'posts_per_page' => 6,
					'no_found_rows'  => true,
				) );

				if ( $home_programs->have_posts() ) :
					while ( $home_programs->have_posts() ) : $home_programs->the_post();
						$icon = get_post_meta( get_the_ID(), 'alkautsar_program_icon', true ) ?: 'book';
						?>
						<article <?php post_class( 'program-card' ); ?>>
							<div class="program-card__icon">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo alkautsar_program_icon_svg( $icon ); // phpcs:ignore ?></svg>
							</div>
							<h3 class="program-card__title"><?php the_title(); ?></h3>
							<p class="program-card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
							<a href="<?php the_permalink(); ?>" class="program-card__link">
								<?php esc_html_e( 'Selengkapnya', 'alkautsar' ); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
							</a>
						</article>
					<?php endwhile; wp_reset_postdata();
				else :
					// Fallback demo cards — link tetap ke /program archive.
					$demo_programs = array(
						array( 'Kajian Rutin Pekanan', 'Pengajian tafsir, fiqih, dan akidah bersama para asatidz setiap ba\'da Maghrib.', 'book' ),
						array( 'Taman Pendidikan Al-Qur\'an', 'Pendidikan mengaji dan akhlak untuk anak-anak usia 4-12 tahun setiap sore.', 'graduation' ),
						array( 'Santunan Dhuafa & Yatim', 'Program rutin santunan bulanan bagi dhuafa, anak yatim, dan kaum duafa.', 'heart' ),
						array( 'Jumat Berkah', 'Aksi sosial nasi kotak untuk pekerja dan musafir setiap hari Jumat.', 'users' ),
						array( 'Pelatihan Kepemudaan', 'Pengembangan keterampilan dan kepemimpinan remaja masjid (risma).', 'cube' ),
						array( 'Konsultasi Syariah', 'Layanan tanya jawab dan konsultasi syariah bersama Dewan Syariah masjid.', 'info' ),
					);
					foreach ( $demo_programs as $p ) :
						list( $title, $desc, $icon ) = $p;
						?>
						<article class="program-card program-card--demo">
							<div class="program-card__icon">
								<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo alkautsar_program_icon_svg( $icon ); // phpcs:ignore ?></svg>
							</div>
							<h3 class="program-card__title"><?php echo esc_html( $title ); ?></h3>
							<p class="program-card__text"><?php echo esc_html( $desc ); ?></p>
							<a href="<?php echo esc_url( home_url( '/program' ) ); ?>" class="program-card__link">
								<?php esc_html_e( 'Selengkapnya', 'alkautsar' ); ?>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
							</a>
						</article>
					<?php endforeach;
				endif;
				?>
			</div>
		</div>
	</section>

        <!-- ═══════════════ UPCOMING EVENTS / KEGIATAN MENDATANG ═══════════════ -->
        <section class="events" id="kegiatan-mendatang">
                <div class="container">
                        <div class="section-head section-head--row">
                                <div>
                                        <p class="section-eyebrow"><?php esc_html_e( 'Agenda Terdekat', 'alkautsar' ); ?></p>
                                        <h2 class="section-title"><?php esc_html_e( 'Kegiatan Mendatang', 'alkautsar' ); ?></h2>
                                        <p class="section-desc"><?php esc_html_e( 'Jangan lewatkan kegiatan dan kajian insya Allah akan diselenggarakan di Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
                                </div>
                                <a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>" class="btn btn--ghost"><?php esc_html_e( 'Lihat Semua Agenda', 'alkautsar' ); ?></a>
                        </div>

                        <div class="events__grid">
                                <?php
                                $events_query = alkautsar_get_upcoming_events( 4 );

                                if ( $events_query->have_posts() ) :
                                        while ( $events_query->have_posts() ) :
                                                $events_query->the_post();
                                                $e_date     = get_post_meta( get_the_ID(), 'alkautsar_event_date', true );
                                                $e_time     = get_post_meta( get_the_ID(), 'alkautsar_event_time', true );
                                                $e_end      = get_post_meta( get_the_ID(), 'alkautsar_event_end_time', true );
                                                $e_loc      = get_post_meta( get_the_ID(), 'alkautsar_event_location', true );
                                                $e_speaker  = get_post_meta( get_the_ID(), 'alkautsar_event_speaker', true );
                                                $e_cat      = get_post_meta( get_the_ID(), 'alkautsar_event_category', true );

                                                $day   = $e_date ? gmdate( 'j', strtotime( $e_date ) ) : '—';
                                                $month = $e_date ? alkautsar_event_month_short( $e_date ) : '';
                                                $year  = $e_date ? gmdate( 'Y', strtotime( $e_date ) ) : '';
                                                ?>
                                                <article <?php post_class( 'event-card' ); ?>>
                                                        <div class="event-card__date">
                                                                <span class="event-card__day"><?php echo esc_html( $day ); ?></span>
                                                                <span class="event-card__month"><?php echo esc_html( $month ); ?></span>
                                                                <span class="event-card__year"><?php echo esc_html( $year ); ?></span>
                                                        </div>
                                                        <div class="event-card__body">
                                                                <?php if ( $e_cat ) : ?>
                                                                        <span class="event-card__cat event-card__cat--<?php echo esc_attr( $e_cat ); ?>"><?php echo esc_html( alkautsar_event_category_label( $e_cat ) ); ?></span>
                                                                <?php endif; ?>
                                                                <h3 class="event-card__title">
                                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                                </h3>
                                                                <ul class="event-card__meta">
                                                                        <?php if ( $e_date ) : ?>
                                                                                <li>
                                                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                                                        <span><?php echo esc_html( alkautsar_format_event_date( $e_date ) ); ?></span>
                                                                                </li>
                                                                        <?php endif; ?>
                                                                        <?php if ( $e_time ) : ?>
                                                                                <li>
                                                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                                                        <span><?php echo esc_html( $e_time . ( $e_end ? ' – ' . $e_end : '' ) . ' WIB' ); ?></span>
                                                                                </li>
                                                                        <?php endif; ?>
                                                                        <?php if ( $e_loc ) : ?>
                                                                                <li>
                                                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                                                        <span><?php echo esc_html( $e_loc ); ?></span>
                                                                                </li>
                                                                        <?php endif; ?>
                                                                        <?php if ( $e_speaker ) : ?>
                                                                                <li>
                                                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                                        <span><?php echo esc_html( $e_speaker ); ?></span>
                                                                                </li>
                                                                        <?php endif; ?>
                                                                </ul>
                                                        </div>
                                                        <a href="<?php the_permalink(); ?>" class="event-card__link" aria-label="<?php esc_attr_e( 'Lihat detail kegiatan', 'alkautsar' ); ?>">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                                        </a>
                                                </article>
                                                <?php
                                        endwhile;
                                        wp_reset_postdata();
                                else :
                                        // Fallback demo events (shown only when no events exist).
                                        $demo_events = array(
                                                array(
                                                        'day'   => '15',
                                                        'month' => 'FEB',
                                                        'year'  => '2026',
                                                        'cat'   => 'kajian',
                                                        'title' => 'Kajian Kitab Bulughul Maram',
                                                        'date'  => 'Sabtu, 15 Februari 2026',
                                                        'time'  => '16:30 – 17:30 WIB',
                                                        'loc'   => 'Aula Masjid Al-Kautsar',
                                                        'spk'   => 'Ustadz Ahmad Fauzi, Lc.',
                                                ),
                                                array(
                                                        'day'   => '23',
                                                        'month' => 'FEB',
                                                        'year'  => '2026',
                                                        'cat'   => 'sosial',
                                                        'title' => 'Buka Puasa Bersama Dhuafa',
                                                        'date'  => 'Ahad, 23 Februari 2026',
                                                        'time'  => '17:00 – 19:00 WIB',
                                                        'loc'   => 'Halaman Masjid',
                                                        'spk'   => 'Panitia Sosial',
                                                ),
                                                array(
                                                        'day'   => '01',
                                                        'month' => 'MAR',
                                                        'year'  => '2026',
                                                        'cat'   => 'pendidikan',
                                                        'title' => 'Pendaftaran TPA Semester Genap',
                                                        'date'  => 'Sabtu, 1 Maret 2026',
                                                        'time'  => '08:00 – 12:00 WIB',
                                                        'loc'   => 'Sekretariat TPA',
                                                        'spk'   => 'Ustadzah Siti Khadijah',
                                                ),
                                                array(
                                                        'day'   => '07',
                                                        'month' => 'MAR',
                                                        'year'  => '2026',
                                                        'cat'   => 'ibadah',
                                                        'title' => 'Maulid Nabi & Pengajian Akbar',
                                                        'date'  => 'Jumat, 7 Maret 2026',
                                                        'time'  => 'Ba\'da Subuh – 11:00 WIB',
                                                        'loc'   => 'Ruang Utama Masjid',
                                                        'spk'   => 'KH. Abdullah Syafi\'i',
                                                ),
                                        );
                                        foreach ( $demo_events as $ev ) :
                                                ?>
                                                <article class="event-card event-card--demo">
                                                        <div class="event-card__date">
                                                                <span class="event-card__day"><?php echo esc_html( $ev['day'] ); ?></span>
                                                                <span class="event-card__month"><?php echo esc_html( $ev['month'] ); ?></span>
                                                                <span class="event-card__year"><?php echo esc_html( $ev['year'] ); ?></span>
                                                        </div>
                                                        <div class="event-card__body">
                                                                <span class="event-card__cat event-card__cat--<?php echo esc_attr( $ev['cat'] ); ?>"><?php echo esc_html( alkautsar_event_category_label( $ev['cat'] ) ); ?></span>
                                                                <h3 class="event-card__title"><a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>"><?php echo esc_html( $ev['title'] ); ?></a></h3>
                                                                <ul class="event-card__meta">
                                                                        <li>
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                                                <span><?php echo esc_html( $ev['date'] ); ?></span>
                                                                        </li>
                                                                        <li>
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                                                <span><?php echo esc_html( $ev['time'] ); ?></span>
                                                                        </li>
                                                                        <li>
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                                                <span><?php echo esc_html( $ev['loc'] ); ?></span>
                                                                        </li>
                                                                        <li>
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                                                                <span><?php echo esc_html( $ev['spk'] ); ?></span>
                                                                        </li>
                                                                </ul>
                                                        </div>
                                                        <a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>" class="event-card__link" aria-label="<?php esc_attr_e( 'Lihat detail kegiatan', 'alkautsar' ); ?>">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                                                        </a>
                                                </article>
                                                <?php
                                        endforeach;
                                endif;
                                ?>
                        </div>

                        <p class="events__footnote">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <?php esc_html_e( 'Jadwal dapat berubah. Pantau pengumuman terbaru melalui WhatsApp atau sosial media masjid.', 'alkautsar' ); ?>
                        </p>
                </div>
        </section>

        <!-- ═══════════════ DONATION CTA ═══════════════ -->
        <section class="donation-cta" id="donation">
                <div class="container">
                        <div class="donation-card">
                                <div class="donation-card__pattern" aria-hidden="true"></div>
                                <div class="donation-card__content">
                                        <p class="section-eyebrow section-eyebrow--gold"><?php esc_html_e( 'Salurkan Kebaikan', 'alkautsar' ); ?></p>
                                        <h2 class="donation-card__title"><?php esc_html_e( 'Investasikan Harta Terbaikmu untuk Rumah Allah', 'alkautsar' ); ?></h2>
                                        <p class="donation-card__text"><?php esc_html_e( 'Setiap rupiah yang Anda salurkan menjadi sebab turunnya berkah, terjaganya rumah Allah, dan terpeliharanya ibadah jamaah. Pilih metode donasi yang paling mudah bagi Anda.', 'alkautsar' ); ?></p>

                                        <div class="donation-methods">
                                                <div class="donation-method">
                                                        <div class="donation-method__head">
                                                                <span class="donation-method__badge"><?php esc_html_e( 'Transfer Bank', 'alkautsar' ); ?></span>
                                                        </div>
                                                        <p class="donation-method__bank"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_name', 'Bank Syariah Indonesia (BSI)' ) ); ?></p>
                                                        <p class="donation-method__number" id="bank-number"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_account', '1234567890' ) ); ?></p>
                                                        <p class="donation-method__holder"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_holder', 'Yayasan Masjid Al-Kautsar' ) ); ?></p>
                                                        <button class="btn btn--outline btn--sm js-copy" data-copy="<?php echo esc_attr( get_theme_mod( 'alkautsar_bank_account', '1234567890' ) ); ?>" type="button">
                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                                                <?php esc_html_e( 'Salin Nomor Rekening', 'alkautsar' ); ?>
                                                        </button>
                                                </div>

                                                <div class="donation-method donation-method--qris">
                                                        <div class="donation-method__head">
                                                                <span class="donation-method__badge"><?php esc_html_e( 'QRIS', 'alkautsar' ); ?></span>
                                                                <span class="donation-method__sub"><?php esc_html_e( 'Scan dari semua e-wallet & m-banking', 'alkautsar' ); ?></span>
                                                        </div>
                                                        <div class="donation-method__qr">
                                                                <?php
                                                                $qris = get_theme_mod( 'alkautsar_qris_image' );
                                                                if ( $qris ) {
                                                                        echo '<img src="' . esc_url( $qris ) . '" alt="' . esc_attr__( 'QRIS Masjid Al-Kautsar', 'alkautsar' ) . '" loading="lazy">';
                                                                } else {
                                                                        echo '<div class="donation-method__qr-placeholder"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><line x1="14" y1="14" x2="14" y2="21"/><line x1="18" y1="14" x2="18" y2="18"/><line x1="21" y1="14" x2="21" y2="21"/><line x1="14" y1="18" x2="18" y2="18"/><line x1="18" y1="21" x2="21" y2="21"/></svg><p>' . esc_html__( 'Unggah QRIS Anda melalui Customizer', 'alkautsar' ) . '</p></div>';
                                                                }
                                                                ?>
                                                        </div>
                                                </div>
                                        </div>

                                        <div class="donation-confirm">
                                                <h3 class="donation-confirm__title"><?php esc_html_e( 'Konfirmasi Donasi', 'alkautsar' ); ?></h3>
                                                <p class="donation-confirm__text"><?php esc_html_e( 'Setelah transfer atau scan QRIS, mohon konfirmasi via WhatsApp agar donasi dapat kami catat dan akui dengan baik.', 'alkautsar' ); ?></p>
                                                <a href="<?php echo esc_url( alkautsar_whatsapp_link( 'Assalamualaikum, saya ingin konfirmasi donasi untuk Masjid Al-Kautsar.' ) ); ?>" class="btn btn--whatsapp btn--lg" target="_blank" rel="noopener noreferrer">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                                                        <?php esc_html_e( 'Konfirmasi via WhatsApp', 'alkautsar' ); ?>
                                                </a>
                                        </div>
                                </div>
                        </div>
                </div>
        </section>

        <!-- ═══════════════ NEWS ═══════════════ -->
        <section class="news" id="news">
                <div class="container">
                        <div class="section-head section-head--row">
                                <div>
                                        <p class="section-eyebrow"><?php esc_html_e( 'Kabar Terkini', 'alkautsar' ); ?></p>
                                        <h2 class="section-title"><?php esc_html_e( 'Berita & Kegiatan Masjid', 'alkautsar' ); ?></h2>
                                </div>
                                <a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="btn btn--ghost"><?php esc_html_e( 'Lihat Semua', 'alkautsar' ); ?></a>
                        </div>
                        <div class="news__grid">
                                <?php
                                $news_query = new WP_Query( array(
                                        'post_type'      => 'post',
                                        'posts_per_page' => 3,
                                        'no_found_rows'  => true,
                                ) );

                                if ( $news_query->have_posts() ) :
                                        while ( $news_query->have_posts() ) :
                                                $news_query->the_post();
                                                ?>
                                                <article <?php post_class( 'news-card' ); ?>>
                                                        <a href="<?php the_permalink(); ?>" class="news-card__media">
                                                                <?php if ( has_post_thumbnail() ) : ?>
                                                                        <?php the_post_thumbnail( 'alkautsar-card', array( 'loading' => 'lazy' ) ); ?>
                                                                <?php else : ?>
                                                                        <div class="news-card__placeholder">
                                                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                                                        </div>
                                                                <?php endif; ?>
                                                                <span class="news-card__date"><?php echo esc_html( get_the_date( 'j M' ) ); ?></span>
                                                        </a>
                                                        <div class="news-card__body">
                                                                <?php alkautsar_entry_categories(); ?>
                                                                <h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                                                <p class="news-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18, '…' ) ); ?></p>
                                                                <a href="<?php the_permalink(); ?>" class="news-card__more"><?php esc_html_e( 'Baca selengkapnya', 'alkautsar' ); ?> →</a>
                                                        </div>
                                                </article>
                                                <?php
                                        endwhile;
                                        wp_reset_postdata();
                                else :
                                        // Demo cards so the homepage is visually complete before any posts exist.
                                        $demo = array(
                                                array( 'Kajian Kitab Bulughul Maram', 'Setiap ba’da Maghrib bersama Ustadz Ahmad Fauzi, insya Allah dimulai seleks isya.', 'Kajian' ),
                                                array( 'Buka Puasa Bersama Dhuafa', 'Masjid Al-Kautsar mengundang jamaah untuk berbagi 200 paket buka puasa.', 'Sosial' ),
                                                array( 'Pendaftaran TPA Semester Baru', 'Dibuka pendaftaran santri baru Taman Pendidikan Al-Qur’an tahun ajaran baru.', 'Pendidikan' ),
                                        );
                                        foreach ( $demo as $item ) :
                                                ?>
                                                <article class="news-card news-card--demo">
                                                        <div class="news-card__media news-card__placeholder">
                                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                                                <span class="news-card__date"><?php echo esc_html( wp_date( 'j M' ) ); ?></span>
                                                        </div>
                                                        <div class="news-card__body">
                                                                <span class="cat-links"><?php echo esc_html( $item[2] ); ?></span>
                                                                <h3 class="news-card__title"><a href="<?php echo esc_url( home_url( '/berita' ) ); ?>"><?php echo esc_html( $item[0] ); ?></a></h3>
                                                                <p class="news-card__excerpt"><?php echo esc_html( $item[1] ); ?></p>
                                                                <a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="news-card__more"><?php esc_html_e( 'Baca selengkapnya', 'alkautsar' ); ?> →</a>
                                                        </div>
                                                </article>
                                                <?php
                                        endforeach;
                                endif;
                                ?>
                        </div>
                </div>
        </section>

        <!-- ═══════════════ TRANSPARENCY ═══════════════ -->
        <section class="transparency" id="transparency">
                <div class="container">
                        <div class="transparency__inner">
                                <div class="transparency__content">
                                        <p class="section-eyebrow"><?php echo esc_html( get_theme_mod( 'alkautsar_transparency_eyebrow', __( 'Transparansi Donasi', 'alkautsar' ) ) ); ?></p>
                                        <h2 class="section-title"><?php echo esc_html( get_theme_mod( 'alkautsar_transparency_title', __( 'Amanah Anda, Kami Jaga dengan Terbuka', 'alkautsar' ) ) ); ?></h2>
                                        <p class="transparency__text"><?php echo esc_html( get_theme_mod( 'alkautsar_transparency_text', __( 'Setiap donasi yang masuk dicatat, dikelola, dan dilaporkan secara terbuka. Kami percaya transparansi adalah bentuk amanah dan jaminan keberkahan setiap rupiah yang Anda titipkan untuk umat.', 'alkautsar' ) ) ); ?></p>
                                        

				<?php
				// Mini beneficiary stats di homepage.
				$beneficiary_defaults = array(
					1 => array( 'count' => '15', 'label' => __( 'Anak Yatim', 'alkautsar' ) ),
					2 => array( 'count' => '25', 'label' => __( 'Fakir Miskin', 'alkautsar' ) ),
					3 => array( 'count' => '12', 'label' => __( 'Janda', 'alkautsar' ) ),
					4 => array( 'count' => '',   'label' => '' ),
					5 => array( 'count' => '',   'label' => '' ),
				);
				$homepage_beneficiaries = array();
				$homepage_total = 0;
				for ( $bi = 1; $bi <= 5; $bi++ ) {
					$bcount = (int) preg_replace( '/[^0-9]/', '', get_theme_mod( "alkautsar_beneficiary_{$bi}_count", $beneficiary_defaults[ $bi ]['count'] ) );
					$blabel = get_theme_mod( "alkautsar_beneficiary_{$bi}_label", $beneficiary_defaults[ $bi ]['label'] );
					if ( $bcount > 0 && $blabel ) {
						$homepage_beneficiaries[] = array( 'count' => $bcount, 'label' => $blabel );
						$homepage_total += $bcount;
					}
				}
				if ( ! empty( $homepage_beneficiaries ) ) :
					?>
					<div class="transparency__beneficiaries">
						<p class="transparency__beneficiaries-label"><?php esc_html_e( 'Penerima Manfaat:', 'alkautsar' ); ?></p>
						<?php foreach ( $homepage_beneficiaries as $hb ) : ?>
							<span class="transparency__beneficiary-chip">
								<strong><?php echo esc_html( $hb['count'] ); ?></strong> <?php echo esc_html( $hb['label'] ); ?>
							</span>
						<?php endforeach; ?>
						<span class="transparency__beneficiary-chip transparency__beneficiary-chip--total">
							<strong><?php echo esc_html( $homepage_total ); ?></strong> <?php esc_html_e( 'Total', 'alkautsar' ); ?>
						</span>
					</div>
				<?php endif; ?>
				="<?php echo esc_url( home_url( '/transparansi' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Lihat Laporan Keuangan', 'alkautsar' ); ?></a>
                                </div>
                                <div class="transparency__visual">
                                        <div class="transparency__chart" aria-hidden="true">
                                                <div class="bar" style="--h:65%"><span>Q1</span></div>
                                                <div class="bar" style="--h:80%"><span>Q2</span></div>
                                                <div class="bar" style="--h:50%"><span>Q3</span></div>
                                                <div class="bar" style="--h:92%"><span>Q4</span></div>
                                        </div>
                                </div>
                        </div>
                </div>
        </section>

        <!-- ═══════════════ CONTACT ═══════════════ -->
        <section class="contact" id="contact">
                <div class="container">
                        <div class="section-head section-head--center">
                                <p class="section-eyebrow"><?php esc_html_e( 'Hubungi Kami', 'alkautsar' ); ?></p>
                                <h2 class="section-title"><?php esc_html_e( 'Sampaikan Pertanyaan & Saran Anda', 'alkautsar' ); ?></h2>
                        </div>
                        <div class="contact__grid">
                                <div class="contact__info">
                                        <ul class="contact__list">
                                                <li>
                                                        <span class="contact__icon">
                                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                        </span>
                                                        <div>
                                                                <p class="contact__label"><?php esc_html_e( 'Alamat', 'alkautsar' ); ?></p>
                                                                <p class="contact__value"><?php alkautsar_address(); ?></p>
                                                        </div>
                                                </li>
                                                <li>
                                                        <span class="contact__icon">
                                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                                        </span>
                                                        <div>
                                                                <p class="contact__label"><?php esc_html_e( 'Telepon', 'alkautsar' ); ?></p>
                                                                <p class="contact__value"><?php echo esc_html( get_theme_mod( 'alkautsar_phone', '(021) 1234 5678' ) ); ?></p>
                                                        </div>
                                                </li>
                                                <li>
                                                        <span class="contact__icon">
                                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                                        </span>
                                                        <div>
                                                                <p class="contact__label"><?php esc_html_e( 'Email', 'alkautsar' ); ?></p>
                                                                <p class="contact__value"><?php echo esc_html( get_theme_mod( 'alkautsar_email', 'info@masjidal-kautsar.id' ) ); ?></p>
                                                        </div>
                                                </li>
                                                <li>
                                                        <span class="contact__icon contact__icon--wa">
                                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg>
                                                        </span>
                                                        <div>
                                                                <p class="contact__label"><?php esc_html_e( 'WhatsApp', 'alkautsar' ); ?></p>
                                                                <p class="contact__value">
                                                                        <a href="<?php echo esc_url( alkautsar_whatsapp_link() ); ?>" target="_blank" rel="noopener noreferrer">
                                                                                <?php echo esc_html( '+' . preg_replace( '/[^0-9]/', '', get_theme_mod( 'alkautsar_whatsapp', '6281234567890' ) ) ); ?>
                                                                        </a>
                                                                </p>
                                                        </div>
                                                </li>
                                        </ul>
                                </div>
                                <div class="contact__form-wrap">
                                        <?php
                                        // Use a contact form plugin (e.g., Contact Form 7 / WPForms) on this page via shortcode,
                                        // OR a simple WhatsApp-only fallback for security (no server-side form processing).
                                        $contact_shortcode = get_post_meta( get_the_ID(), 'alkautsar_contact_shortcode', true );
                                        if ( $contact_shortcode ) {
                                                echo do_shortcode( $contact_shortcode );
                                        } else {
                                                // WhatsApp-based contact: avoids handling PII server-side (OWASP A04:2021 — Insecure Design).
                                                ?>
                                                <form class="contact-form" id="whatsapp-contact" onsubmit="return alkautsarSendWA(event);">
                                                        <p class="contact-form__note"><?php esc_html_e( 'Pesan Anda akan dikirim langsung melalui WhatsApp.', 'alkautsar' ); ?></p>
                                                        <div class="form-row">
                                                                <label for="cf-name"><?php esc_html_e( 'Nama', 'alkautsar' ); ?></label>
                                                                <input type="text" id="cf-name" name="name" required maxlength="80">
                                                        </div>
                                                        <div class="form-row">
                                                                <label for="cf-phone"><?php esc_html_e( 'No. WhatsApp Anda', 'alkautsar' ); ?></label>
                                                                <input type="tel" id="cf-phone" name="phone" required maxlength="20" inputmode="tel">
                                                        </div>
                                                        <div class="form-row">
                                                                <label for="cf-message"><?php esc_html_e( 'Pesan', 'alkautsar' ); ?></label>
                                                                <textarea id="cf-message" name="message" rows="4" required maxlength="500"></textarea>
                                                        </div>
                                                        <button type="submit" class="btn btn--whatsapp btn--lg btn--block">
                                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                                                <?php esc_html_e( 'Kirim via WhatsApp', 'alkautsar' ); ?>
                                                        </button>
                                                </form>
                                                <script>
                                                        function alkautsarSendWA(e) {
                                                                e.preventDefault();
                                                                var wa = '<?php echo esc_js( alkautsar_whatsapp_link() ); ?>';
                                                                var name = document.getElementById('cf-name').value.trim();
                                                                var phone = document.getElementById('cf-phone').value.trim();
                                                                var msg = document.getElementById('cf-message').value.trim();
                                                                var text = 'Assalamualaikum, saya ' + name + ' (' + phone + '):\n\n' + msg;
                                                                window.open(wa + '?text=' + encodeURIComponent(text), '_blank', 'noopener');
                                                                return false;
                                                        }
                                                </script>
                                                <?php
                                        }
                                        ?>
                                </div>
                        </div>
                </div>
        </section>

</main>

<?php
get_footer();
