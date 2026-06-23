<?php
/**
 * Template Name: Galeri Foto (Archive)
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$paged     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$cat_query = isset( $_GET['kategori'] ) ? sanitize_text_field( wp_unslash( $_GET['kategori'] ) ) : '';
$albums    = alkautsar_get_galeri_albums( 12, $cat_query );
$categories = alkautsar_get_galeri_categories();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Galeri Foto Kegiatan', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Dokumentasi kegiatan dan acara Masjid Al-Kautsar. Klik album untuk melihat semua foto.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">

		<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
			<div class="galeri-filters">
				<a href="<?php echo esc_url( home_url( '/galeri' ) ); ?>" class="galeri-filter <?php echo '' === $cat_query ? 'is-active' : ''; ?>">
					<?php esc_html_e( 'Semua', 'alkautsar' ); ?>
				</a>
				<?php foreach ( $categories as $cat ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'kategori', $cat->slug, home_url( '/galeri' ) ) ); ?>" class="galeri-filter <?php echo $cat_query === $cat->slug ? 'is-active' : ''; ?>">
						<?php echo esc_html( $cat->name ); ?>
						<span class="galeri-filter__count"><?php echo esc_html( $cat->count ); ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $albums->have_posts() ) : ?>
			<div class="galeri-grid">
				<?php while ( $albums->have_posts() ) : $albums->the_post();
					$photos = alkautsar_get_galeri_photos();
					$count = count( $photos );
					?>
					<a href="<?php the_permalink(); ?>" class="galeri-album">
						<div class="galeri-album__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'alkautsar-card', array( 'loading' => 'lazy', 'class' => 'galeri-album__img' ) ); ?>
							<?php else : ?>
								<div class="galeri-album__placeholder">
									<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
								</div>
							<?php endif; ?>
							<span class="galeri-album__count">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
								<?php echo esc_html( $count ); ?> foto
							</span>
						</div>
						<div class="galeri-album__body">
							<h3 class="galeri-album__title"><?php the_title(); ?></h3>
							<?php if ( has_excerpt() ) : ?>
								<p class="galeri-album__desc"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 15, '…' ) ); ?></p>
							<?php endif; ?>
							<span class="galeri-album__date"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
						</div>
					</a>
				<?php endwhile; ?>
			</div>

			<?php if ( $albums->max_num_pages > 1 ) : ?>
				<div class="pagination">
					<?php
					echo paginate_links( array(
						'total'     => $albums->max_num_pages,
						'current'   => $paged,
						'prev_text' => '<span aria-hidden="true">&larr;</span> <span class="screen-reader-text">' . esc_html__( 'Sebelumnya', 'alkautsar' ) . '</span>',
						'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Berikutnya', 'alkautsar' ) . '</span> <span aria-hidden="true">&rarr;</span>',
						'mid_size'  => 1,
					) );
					?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<p class="galeri-empty">
				<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
				<?php esc_html_e( 'Belum ada album. Admin dapat menambahkannya dari menu "Galeri Foto" di dashboard.', 'alkautsar' ); ?>
			</p>
		<?php endif; wp_reset_postdata(); ?>
	</div>
</main>

<?php get_footer(); ?>
