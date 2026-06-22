<?php
/**
 * Single program template.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

while ( have_posts() ) :
	the_post();
	$icon     = get_post_meta( get_the_ID(), 'alkautsar_program_icon', true ) ?: 'book';
	$schedule = get_post_meta( get_the_ID(), 'alkautsar_program_schedule', true );
	$location = get_post_meta( get_the_ID(), 'alkautsar_program_location', true );
	?>
	<header class="page-header">
		<div class="container">
			<div class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'alkautsar' ); ?></a> ›
				<a href="<?php echo esc_url( home_url( '/program' ) ); ?>"><?php esc_html_e( 'Program', 'alkautsar' ); ?></a>
			</div>
		</div>
	</header>

	<main id="primary" class="site-main">
		<div class="container single-content">
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header" style="text-align:center; margin-bottom:2rem;">
					<div class="program-card__icon" style="margin:0 auto 1.25rem;">
						<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><?php echo alkautsar_program_icon_svg( $icon ); // phpcs:ignore ?></svg>
					</div>
					<h1 class="entry-title" style="margin:0 auto 1rem; max-width:800px;"><?php the_title(); ?></h1>
					<?php if ( $schedule || $location ) : ?>
						<div class="entry-meta" style="justify-content:center; border:0;">
							<?php if ( $schedule ) : ?>
								<span>📅 <?php echo esc_html( $schedule ); ?></span>
							<?php endif; ?>
							<?php if ( $location ) : ?>
								<span>📍 <?php echo esc_html( $location ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail" style="margin:0 auto 2rem; max-width:1000px; border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-md);">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="entry-content">
					<?php the_content(); ?>
				</div>

				<footer class="entry-footer" style="max-width:760px; margin:2rem auto 0; padding-top:1.5rem; border-top:1px solid var(--line); text-align:center;">
					<a href="<?php echo esc_url( home_url( '/program' ) ); ?>">← <?php esc_html_e( 'Kembali ke Program', 'alkautsar' ); ?></a>
				</footer>
			</article>
		</div>
	</main>
	<?php
endwhile;

get_footer();
