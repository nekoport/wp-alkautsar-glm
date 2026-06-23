<?php
/**
 * Single galeri template — tampilkan semua foto dalam album.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

while ( have_posts() ) : the_post();
	$photos = alkautsar_get_galeri_photos();
	$count = count( $photos );
	$categories = get_the_terms( get_the_ID(), 'galeri_category' );
	?>
	<header class="page-header">
		<div class="container">
			<div class="breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'alkautsar' ); ?></a> &rsaquo;
				<a href="<?php echo esc_url( home_url( '/galeri' ) ); ?>"><?php esc_html_e( 'Galeri', 'alkautsar' ); ?></a>
			</div>
		</div>
	</header>

	<main id="primary" class="site-main">
		<div class="container single-content" style="max-width:1100px;">

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header" style="text-align:left; margin-bottom:2rem;">
					<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
						<p style="margin:0 0 0.5rem;">
							<?php foreach ( $categories as $cat ) : ?>
								<span class="event-card__cat event-card__cat--kajian" style="display:inline-block; margin-right:0.375rem;"><?php echo esc_html( $cat->name ); ?></span>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>
					<h1 class="entry-title" style="margin:0 0 0.75rem;"><?php the_title(); ?></h1>
					<div class="entry-meta" style="justify-content:flex-start; border:0;">
						<span><?php echo esc_html( get_the_date( 'l, j F Y' ) ); ?></span>
						<span>&middot;</span>
						<span><?php echo esc_html( sprintf( _n( '%d foto', '%d foto', $count, 'alkautsar' ), $count ) ); ?></span>
					</div>
				</header>

				<?php if ( has_excerpt() ) : ?>
					<div class="entry-content" style="margin-bottom:2rem; padding:1.25rem 1.5rem; background:var(--base-alt); border-left:4px solid var(--accent); border-radius:var(--radius-sm); font-size:1.0625rem; line-height:1.7;">
						<?php the_excerpt(); ?>
					</div>
				<?php endif; ?>

				<?php
				$content = get_the_content();
				if ( $content ) :
					?>
					<div class="entry-content" style="margin-bottom:2.5rem;">
						<?php the_content(); ?>
					</div>
				<?php endif; ?>

				<?php if ( $count > 0 ) : ?>
					<div class="galeri-photos-grid">
						<?php foreach ( $photos as $idx => $photo_id ) :
							$thumb = wp_get_attachment_image_src( $photo_id, 'alkautsar-card' );
							$full  = wp_get_attachment_image_src( $photo_id, 'full' );
							$alt   = get_post_meta( $photo_id, '_wp_attachment_image_alt', true );
							if ( ! $thumb ) { continue; }
							?>
							<figure class="galeri-photo" data-full="<?php echo esc_url( $full ? $full[0] : $thumb[0] ); ?>" data-title="<?php echo esc_attr( $alt ); ?>" data-index="<?php echo esc_attr( $idx ); ?>">
								<img src="<?php echo esc_url( $thumb[0] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" class="galeri-photo__img">
								<span class="galeri-photo__zoom">
									<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
								</span>
							</figure>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<p style="text-align:center; padding:3rem; color:var(--ink-soft); background:var(--base-alt); border-radius:var(--radius-lg);">
						<?php esc_html_e( 'Belum ada foto di album ini.', 'alkautsar' ); ?>
					</p>
				<?php endif; ?>

				<footer class="entry-footer" style="margin-top:2.5rem; padding-top:1.5rem; border-top:1px solid var(--line); text-align:center;">
					<a href="<?php echo esc_url( home_url( '/galeri' ) ); ?>">&larr; <?php esc_html_e( 'Kembali ke Galeri', 'alkautsar' ); ?></a>
				</footer>
			</article>

		</div>
	</main>

	<!-- Lightbox -->
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
			<p class="galeri-lightbox__counter"></p>
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
		var counter = lightbox.querySelector('.galeri-lightbox__counter');
		var items = Array.prototype.slice.call(document.querySelectorAll('.galeri-photo'));
		var currentIndex = 0;
		var total = items.length;

		function open(index) {
			currentIndex = index;
			var item = items[index];
			img.src = item.getAttribute('data-full');
			img.alt = item.getAttribute('data-title');
			caption.textContent = item.getAttribute('data-title');
			counter.textContent = (currentIndex + 1) + ' / ' + total;
			lightbox.hidden = false;
			document.body.style.overflow = 'hidden';
		}
		function close() {
			lightbox.hidden = true;
			document.body.style.overflow = '';
		}
		function navigate(dir) {
			currentIndex = (currentIndex + dir + total) % total;
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
	<?php
endwhile;

get_footer();
