<?php
/**
 * Template Name: Program
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Program & Kegiatan', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Beragam program rutin yang diselenggarakan Masjid Al-Kautsar untuk umat.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">
		<div class="section-head section-head--center" style="margin-bottom:3rem;">
			<p class="section-eyebrow"><?php esc_html_e( 'Program Unggulan', 'alkautsar' ); ?></p>
			<h2 class="section-title"><?php esc_html_e( 'Kegiatan Rutin Masjid', 'alkautsar' ); ?></h2>
			<p class="section-desc"><?php esc_html_e( 'Program-program berikut dijalankan secara berkala untuk memperkuat iman, ilmu, dan ukhuwah jamaah.', 'alkautsar' ); ?></p>
		</div>

		<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$programs_query = new WP_Query( array(
			'post_type'      => 'program',
			'posts_per_page' => 9,
			'paged'          => $paged,
		) );

		if ( $programs_query->have_posts() ) :
			?>
			<div class="programs__grid" style="grid-template-columns: repeat(3, 1fr);">
				<?php while ( $programs_query->have_posts() ) : $programs_query->the_post();
					$icon     = get_post_meta( get_the_ID(), 'alkautsar_program_icon', true ) ?: 'book';
					$schedule = get_post_meta( get_the_ID(), 'alkautsar_program_schedule', true );
					$location = get_post_meta( get_the_ID(), 'alkautsar_program_location', true );
					?>
					<article <?php post_class( 'program-card' ); ?>>
						<div class="program-card__icon">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo alkautsar_program_icon_svg( $icon ); // phpcs:ignore ?></svg>
						</div>
						<h3 class="program-card__title"><?php the_title(); ?></h3>
						<p class="program-card__text"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
						<?php if ( $schedule || $location ) : ?>
							<ul class="program-card__meta">
								<?php if ( $schedule ) : ?>
									<li>
										<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
										<span><?php echo esc_html( $schedule ); ?></span>
									</li>
								<?php endif; ?>
								<?php if ( $location ) : ?>
									<li>
										<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
										<span><?php echo esc_html( $location ); ?></span>
									</li>
								<?php endif; ?>
							</ul>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" class="program-card__link">
							<?php esc_html_e( 'Selengkapnya', 'alkautsar' ); ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
						</a>
					</article>
				<?php endwhile; ?>
			</div>

			<div class="pagination" style="margin-top:2.5rem; text-align:center;">
				<?php
				echo paginate_links( array(
					'total'     => $programs_query->max_num_pages,
					'current'   => $paged,
					'prev_text' => '← ' . __( 'Sebelumnya', 'alkautsar' ),
					'next_text' => __( 'Berikutnya', 'alkautsar' ) . ' →',
				) );
				?>
			</div>
		<?php
		else :
			// Fallback: tampilkan 6 program demo supaya halaman tidak kosong.
			?>
			<div class="programs__grid" style="grid-template-columns: repeat(3, 1fr);">
				<?php
				$demo = array(
					array( 'Kajian Rutin Pekanan', 'Pengajian tafsir, fiqih, dan akidah bersama para asatidz setiap ba\'da Maghrib.', 'book', 'Setiap ba\'da Maghrib', 'Aula Masjid' ),
					array( 'Taman Pendidikan Al-Qur\'an', 'Pendidikan mengaji dan akhlak untuk anak-anak usia 4–12 tahun setiap sore.', 'graduation', 'Senin–Jumat 16:00', 'Ruang TPA' ),
					array( 'Santunan Dhuafa & Yatim', 'Program rutin santunan bulanan bagi dhuafa, anak yatim, dan kaum duafa.', 'heart', 'Setiap akhir bulan', 'Aula Masjid' ),
					array( 'Jumat Berkah', 'Aksi sosial nasi kotak untuk pekerja dan musafir setiap hari Jumat.', 'users', 'Setiap Jumat 11:30', 'Halaman Masjid' ),
					array( 'Pelatihan Kepemudaan', 'Pengembangan keterampilan dan kepemimpinan remaja masjid (risma).', 'cube', 'Setiap Sabtu', 'Ruang Risma' ),
					array( 'Konsultasi Syariah', 'Layanan tanya jawab dan konsultasi syariah bersama Dewan Syariah masjid.', 'info', 'By appointment', 'Sekretariat' ),
				);
				foreach ( $demo as $p ) :
					list( $title, $desc, $icon, $sched, $loc ) = $p;
					?>
					<article class="program-card program-card--demo">
						<div class="program-card__icon">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo alkautsar_program_icon_svg( $icon ); // phpcs:ignore ?></svg>
						</div>
						<h3 class="program-card__title"><?php echo esc_html( $title ); ?></h3>
						<p class="program-card__text"><?php echo esc_html( $desc ); ?></p>
						<ul class="program-card__meta">
							<li>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
								<span><?php echo esc_html( $sched ); ?></span>
							</li>
							<li>
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
								<span><?php echo esc_html( $loc ); ?></span>
							</li>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>

			<p style="text-align:center; margin-top:2rem; padding:1.25rem; background:var(--base-alt); border-radius:var(--radius-md); color:var(--ink-soft); font-size:0.9375rem;">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:0.5rem; color:var(--accent-deep);"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
				<?php esc_html_e( 'Ini adalah contoh program. Admin dapat menambahkan program asli melalui dashboard → Program → Tambah Program.', 'alkautsar' ); ?>
			</p>
		<?php
		endif;
		wp_reset_postdata();
		?>

		<div style="text-align:center; margin-top:4rem; padding:2rem; background: var(--base-alt); border-radius: var(--radius-lg);">
			<h3 style="margin-bottom:1rem; font-size:1.5rem;"><?php esc_html_e( 'Ingin lihat agenda kegiatan terdekat?', 'alkautsar' ); ?></h3>
			<p style="margin-bottom:1.5rem; color: var(--ink-soft);"><?php esc_html_e( 'Lihat jadwal lengkap kajian, pengajian, dan acara khusus di halaman Kegiatan.', 'alkautsar' ); ?></p>
			<a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>" class="btn btn--primary">
				<?php esc_html_e( 'Lihat Agenda Mendatang', 'alkautsar' ); ?>
				<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
			</a>
		</div>
	</div>
</main>

<?php get_footer(); ?>
