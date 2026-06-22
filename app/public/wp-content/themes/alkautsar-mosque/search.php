<?php
/**
 * Search results template.
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
		<h1>
			<?php
			/* translators: %s: search query. */
			printf( esc_html__( 'Hasil pencarian untuk: %s', 'alkautsar' ), '<span style="color:var(--accent);">' . esc_html( get_search_query() ) . '</span>' );
			?>
		</h1>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">
		<?php if ( have_posts() ) : ?>
			<div class="news__grid" style="grid-template-columns: repeat(2, 1fr);">
				<?php
				while ( have_posts() ) :
					the_post();
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
							<p class="news-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
							<a href="<?php the_permalink(); ?>" class="news-card__more"><?php esc_html_e( 'Baca selengkapnya', 'alkautsar' ); ?> →</a>
						</div>
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
				<?php esc_html_e( 'Tidak ada hasil ditemukan. Coba kata kunci lain.', 'alkautsar' ); ?>
			</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
