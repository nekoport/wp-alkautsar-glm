<?php
/**
 * Single post template.
 * Layout: 2 kolom (konten utama + sidebar donasi).
 * Setelah konten: berita terkait + box donasi.
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
		<?php
		$categories = get_the_category();
		if ( $categories ) {
			echo '<div class="breadcrumb">';
			echo '<a href="' . esc_url( home_url( '/berita' ) ) . '">' . esc_html__( 'Berita', 'alkautsar' ) . '</a>';
			echo ' &rsaquo; ';
			echo esc_html( $categories[0]->name );
			echo '</div>';
		}
		?>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container single-content" style="max-width:1200px;">
		<div class="single-layout">
			<!-- ════ KIRI: KONTEN UTAMA ════ -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--with-sidebar' ); ?>>

				<?php while ( have_posts() ) : the_post(); ?>

					<header class="entry-header" style="text-align:left; margin-bottom:1.5rem;">
						<?php alkautsar_entry_categories(); ?>
						<h1 class="entry-title" style="margin:0.5rem 0 1rem;"><?php the_title(); ?></h1>
						<div class="entry-meta" style="justify-content:flex-start; border:0;">
							<?php alkautsar_posted_on(); ?>
							<?php
							// Reading time.
							$content = get_the_content();
							$word_count = str_word_count( wp_strip_all_tags( $content ) );
							$reading_time = max( 1, ceil( $word_count / 200 ) );
							echo '<span>&middot;</span><span>' . esc_html( sprintf( __( '%d menit baca', 'alkautsar' ), $reading_time ) ) . '</span>';
							?>
						</div>
					</header>

					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="entry-thumbnail" style="margin:0 0 1.5rem; border-radius: var(--radius-lg); overflow:hidden; box-shadow: var(--shadow-md);">
							<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
						</figure>
					<?php endif; ?>

					<div class="entry-content">
						<?php
						the_content();
						wp_link_pages( array(
							'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'alkautsar' ),
							'after'  => '</div>',
						) );
						?>
					</div>

					<!-- Share buttons -->
					<footer class="entry-share" style="margin:2rem 0 0; padding:1.25rem 0; border-top:1px solid var(--line); border-bottom:1px solid var(--line);">
						<p style="font-size:0.875rem; font-weight:600; color:var(--secondary); margin:0 0 0.75rem;">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:0.375rem; color:var(--accent-deep);"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
							<?php esc_html_e( 'Bagikan artikel ini:', 'alkautsar' ); ?>
						</p>
						<div class="entry-share__buttons">
							<?php
							$permalink = rawurlencode( get_permalink() );
							$title     = rawurlencode( get_the_title() );
							$wa_number = preg_replace( '/[^0-9]/', '', get_theme_mod( 'alkautsar_whatsapp', '6281234567890' ) );
							?>
							<a href="https://wa.me/?text=<?php echo esc_attr( $title . ' ' . rawurldecode( $permalink ) ); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-btn--wa" aria-label="Share to WhatsApp">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
								<span>WhatsApp</span>
							</a>
							<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $permalink ); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-btn--fb" aria-label="Share to Facebook">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
								<span>Facebook</span>
							</a>
							<a href="https://t.me/share/url?url=<?php echo esc_attr( $permalink ); ?>&text=<?php echo esc_attr( $title ); ?>" target="_blank" rel="noopener noreferrer" class="share-btn share-btn--tg" aria-label="Share to Telegram">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
								<span>Telegram</span>
							</a>
							<button type="button" class="share-btn share-btn--copy js-share-copy" data-url="<?php echo esc_attr( get_permalink() ); ?>" aria-label="Copy link">
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
								<span>Salin Tautan</span>
							</button>
						</div>
					</footer>

					<footer class="entry-footer" style="margin-top:1.5rem; display:flex; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
						<?php
						$tags = get_the_tag_list( '', ', ' );
						if ( $tags ) {
							echo '<div><strong>' . esc_html__( 'Tag:', 'alkautsar' ) . '</strong> ' . wp_kses_post( $tags ) . '</div>';
						}
						?>
						<a href="<?php echo esc_url( home_url( '/berita' ) ); ?>">&larr; <?php esc_html_e( 'Kembali ke Berita', 'alkautsar' ); ?></a>
					</footer>

				<?php endwhile; ?>
			</article>

			<!-- ════ KANAN: SIDEBAR DONASI ════ -->
			<aside class="single-sidebar">
				<!-- Donation card -->
				<div class="sidebar-donasi">
					<h3 class="sidebar-donasi__title">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
						<?php esc_html_e( 'Salurkan Donasi', 'alkautsar' ); ?>
					</h3>
					<p class="sidebar-donasi__text"><?php esc_html_e( 'Donasi Anda sangat berarti untuk kemakmuran masjid.', 'alkautsar' ); ?></p>

					<div class="sidebar-donasi__bank">
						<p class="sidebar-donasi__bank-name"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_name', 'Bank Syariah Indonesia (BSI)' ) ); ?></p>
						<p class="sidebar-donasi__bank-number"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_account', '1234567890' ) ); ?></p>
						<p class="sidebar-donasi__bank-holder"><?php echo esc_html( get_theme_mod( 'alkautsar_bank_holder', 'Yayasan Masjid Al-Kautsar' ) ); ?></p>
						<button type="button" class="btn btn--gold btn--sm btn--block js-copy" data-copy="<?php echo esc_attr( get_theme_mod( 'alkautsar_bank_account', '1234567890' ) ); ?>">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
							<?php esc_html_e( 'Salin No. Rekening', 'alkautsar' ); ?>
						</button>
					</div>

					<?php
					$qris = get_theme_mod( 'alkautsar_qris_image' );
					if ( $qris ) :
						?>
						<div class="sidebar-donasi__qris">
							<p class="sidebar-donasi__qris-label"><?php esc_html_e( 'Atau scan QRIS:', 'alkautsar' ); ?></p>
							<img src="<?php echo esc_url( $qris ); ?>" alt="<?php esc_attr_e( 'QRIS Masjid Al-Kautsar', 'alkautsar' ); ?>" loading="lazy">
						</div>
					<?php endif; ?>

					<a href="<?php echo esc_url( alkautsar_whatsapp_link( 'Assalamualaikum, saya ingin konfirmasi donasi untuk Masjid Al-Kautsar.' ) ); ?>" class="btn btn--whatsapp btn--block" target="_blank" rel="noopener noreferrer">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
						<?php esc_html_e( 'Konfirmasi via WhatsApp', 'alkautsar' ); ?>
					</a>
				</div>

				<!-- Latest news card -->
				<div class="sidebar-latest">
					<h3 class="sidebar-latest__title">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8V6z"/></svg>
						<?php esc_html_e( 'Berita Terbaru', 'alkautsar' ); ?>
					</h3>
					<ul class="sidebar-latest__list">
						<?php
						$latest = new WP_Query( array(
							'post_type'      => 'post',
							'posts_per_page' => 4,
							'post__not_in'   => array( get_the_ID() ),
							'no_found_rows'  => true,
						) );
						while ( $latest->have_posts() ) :
							$latest->the_post();
							?>
							<li class="sidebar-latest__item">
								<a href="<?php the_permalink(); ?>" class="sidebar-latest__link">
									<?php if ( has_post_thumbnail() ) : ?>
										<span class="sidebar-latest__thumb"><?php the_post_thumbnail( 'thumbnail', array( 'loading' => 'lazy' ) ); ?></span>
									<?php else : ?>
										<span class="sidebar-latest__thumb sidebar-latest__thumb--placeholder">
											<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
										</span>
									<?php endif; ?>
									<span class="sidebar-latest__text">
										<span class="sidebar-latest__title-text"><?php the_title(); ?></span>
										<span class="sidebar-latest__date"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></span>
									</span>
								</a>
							</li>
						<?php endwhile; wp_reset_postdata(); ?>
					</ul>
				</div>
			</aside>
		</div>

		<!-- ════ BERITA TERKAT ════ -->
		<?php
		$cats = wp_get_post_categories( get_the_ID() );
		if ( $cats ) :
			$related = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
				'category__in'   => $cats,
				'no_found_rows'  => true,
			) );
			if ( $related->have_posts() ) :
				?>
				<section class="related-news">
					<div class="section-head section-head--row" style="margin-bottom:1.5rem;">
						<div>
							<p class="section-eyebrow"><?php esc_html_e( 'Baca Juga', 'alkautsar' ); ?></p>
							<h2 class="section-title" style="font-size:1.75rem;"><?php esc_html_e( 'Berita Terkait', 'alkautsar' ); ?></h2>
						</div>
						<a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="btn btn--ghost btn--sm"><?php esc_html_e( 'Lihat Semua', 'alkautsar' ); ?></a>
					</div>
					<div class="news__grid" style="grid-template-columns: repeat(3, 1fr);">
						<?php while ( $related->have_posts() ) : $related->the_post(); ?>
							<article <?php post_class( 'news-card' ); ?>>
								<a href="<?php the_permalink(); ?>" class="news-card__media">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'alkautsar-card', array( 'loading' => 'lazy' ) ); ?>
									<?php else : ?>
										<div class="news-card__placeholder">
											<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
										</div>
									<?php endif; ?>
									<span class="news-card__date"><?php echo esc_html( get_the_date( 'j M' ) ); ?></span>
								</a>
								<div class="news-card__body">
									<?php alkautsar_entry_categories(); ?>
									<h3 class="news-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p class="news-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 14, '&hellip;' ) ); ?></p>
									<a href="<?php the_permalink(); ?>" class="news-card__more"><?php esc_html_e( 'Baca', 'alkautsar' ); ?> &rarr;</a>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
				</section>
				<?php
			endif;
		endif;
		?>
	</div>
</main>

<?php
get_footer();
