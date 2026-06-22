<?php
/**
 * Template Name: Berita & Informasi
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Berita & Informasi', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Kabar terkini seputar kegiatan dan pengumuman Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">
		<?php
		// Search & filter bar.
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
		?>
		<div class="news-filters">
			<form method="get" action="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="news-filters__form">
				<input type="search" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Cari berita...', 'alkautsar' ); ?>" class="news-filters__search">
				<button type="submit" class="btn btn--primary btn--sm"><?php esc_html_e( 'Cari', 'alkautsar' ); ?></button>
			</form>
		</div>

		<?php
		$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 9,
			'paged'          => $paged,
		);
		if ( $search ) {
			$args['s'] = $search;
		}
		$news_query = new WP_Query( $args );

		if ( $news_query->have_posts() ) :
			?>
			<div class="news__grid" style="grid-template-columns: repeat(3, 1fr);">
				<?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
					<article <?php post_class( 'news-card' ); ?>>
						<a href="<?php the_permalink(); ?>" class="news-card__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'alkautsar-card', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<div class="news-card__placeholder">
									<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
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
				<?php endwhile; ?>
			</div>

			<div class="pagination" style="margin-top:2rem; text-align:center;">
				<?php
				echo paginate_links( array(
					'total'     => $news_query->max_num_pages,
					'current'   => $paged,
					'prev_text' => '←',
					'next_text' => '→',
				) );
				?>
			</div>
		<?php else : ?>
			<p style="text-align:center; padding: 4rem 0; color: var(--ink-soft);">
				<?php esc_html_e( 'Belum ada berita. Silakan kembali nanti.', 'alkautsar' ); ?>
			</p>
		<?php endif; wp_reset_postdata(); ?>
	</div>
</main>

<?php get_footer(); ?>
