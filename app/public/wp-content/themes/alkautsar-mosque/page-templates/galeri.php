<?php
/**
 * Template Name: Galeri Foto
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$paged     = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$cat_query = isset( $_GET['kategori'] ) ? sanitize_text_field( wp_unslash( $_GET['kategori'] ) ) : '';
$photos    = alkautsar_get_galeri_photos( 12, $cat_query );
$categories = alkautsar_get_galeri_categories();
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Galeri Foto Kegiatan', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Dokumentasi kegiatan dan acara Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
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

		<?php if ( $photos->have_posts() ) : ?>
			<div class="galeri-grid">
				<?php while ( $photos->have_posts() ) : $photos->the_post(); ?>
					<figure class="galeri-item" data-full="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ); ?>" data-title="<?php the_title_attribute(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'alkautsar-card', array( 'loading' => 'lazy', 'class' => 'galeri-item__img' ) ); ?>
						<?php else : ?>
							<div class="galeri-item__placeholder">
								<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
							</div>
						<?php endif; ?>
						<figcaption class="galeri-item__caption">
							<h3 class="galeri-item__title"><?php the_title(); ?></h3>
							<?php if ( has_excerpt() ) : ?>
								<p class="galeri-item__desc"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
							<span class="galeri-item__zoom">
								<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
							</span>
						</figcaption>
					</figure>
				<?php endwhile; ?>
			</div>

			<?php if ( $photos->max_num_pages > 1 ) : ?>
				<div class="pagination">
					<?php
					echo paginate_links( array(
						'total'     => $photos->max_num_pages,
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
				<?php esc_html_e( 'Belum ada foto. Admin dapat menambahkannya dari menu "Galeri Foto" di dashboard.', 'alkautsar' ); ?>
			</p>
		<?php endif; wp_reset_postdata(); ?>
	</div>
</main>

<!-- Lightbox modal -->
<div class="galeri-lightbox" id="galeri-lightbox" hidden>
	<button class="galeri-lightbox__close" aria-label="<?php esc_attr_e( 'Tutup', 'alkautsar' ); ?>">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
	</button>
	<button class="galeri-lightbox__nav galeri-lightbox__nav--prev" aria-label="<?php esc_attr_e( 'Sebelumnya', 'alkautsar' ); ?>">
		<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
	</button>
	<figure class="galeri-lightbox__figure">
		<img class="galeri-lightbox__img" src="" alt="">
		<figcaption class="galeri-lightbox__caption"></figcaption>
	</figure>
	<button class="galeri-lightbox__nav galeri-lightbox__nav--next" aria-label="<?php esc_attr_e( 'Berikutnya', 'alkautsar' ); ?>">
		<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
	</button>
</div>

<script>
(function() {
	'use strict';
	var lightbox = document.getElementById('galeri-lightbox');
	if (!lightbox) return;
	var img = lightbox.querySelector('.galeri-lightbox__img');
	var caption = lightbox.querySelector('.galeri-lightbox__caption');
	var items = Array.prototype.slice.call(document.querySelectorAll('.galeri-item'));
	var currentIndex = 0;

	function open(index) {
		currentIndex = index;
		var item = items[index];
		img.src = item.getAttribute('data-full');
		caption.textContent = item.getAttribute('data-title');
		lightbox.hidden = false;
		document.body.style.overflow = 'hidden';
	}
	function close() {
		lightbox.hidden = true;
		document.body.style.overflow = '';
	}
	function navigate(dir) {
		currentIndex = (currentIndex + dir + items.length) % items.length;
		open(currentIndex);
	}

	items.forEach(function(item, i) {
		item.addEventListener('click', function() { open(i); });
		item.style.cursor = 'pointer';
	});

	lightbox.querySelector('.galeri-lightbox__close').addEventListener('click', close);
	lightbox.querySelector('.galeri-lightbox__nav--prev').addEventListener('click', function(e) { e.stopPropagation(); navigate(-1); });
	lightbox.querySelector('.galeri-lightbox__nav--next').addEventListener('click', function(e) { e.stopPropagation(); navigate(1); });
	lightbox.addEventListener('click', function(e) { if (e.target === lightbox) close(); });

	document.addEventListener('keydown', function(e) {
		if (lightbox.hidden) return;
		if (e.key === 'Escape') close();
		if (e.key === 'ArrowLeft') navigate(-1);
		if (e.key === 'ArrowRight') navigate(1);
	});
})();
</script>

<?php get_footer(); ?>
