<?php
/**
 * Archive template for the "event" Custom Post Type.
 * Displays all upcoming & past events in a clean grid.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Agenda & Kegiatan Masjid', 'alkautsar' ); ?></h1>
		<p class="breadcrumb" style="margin-top:0.5rem;"><?php esc_html_e( 'Jadwal lengkap kegiatan, kajian, dan acara di Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">
		<?php if ( have_posts() ) : ?>
			<div class="events__grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 2rem;">
				<?php
				while ( have_posts() ) :
					the_post();
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
							<h3 class="event-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
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
				?>
			</div>

			<div class="pagination" style="margin-top:2rem; text-align:center;">
				<?php
				echo paginate_links( array(
					'prev_text' => '←',
					'next_text' => '→',
				) );
				?>
			</div>

		<?php else : ?>
			<p style="text-align:center; padding: 4rem 0; color: var(--ink-soft);">
				<?php esc_html_e( 'Belum ada agenda kegiatan. Silakan kembali nanti.', 'alkautsar' ); ?>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
