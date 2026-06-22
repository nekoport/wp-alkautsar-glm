<?php
/**
 * Single event template.
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
		<div class="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Beranda', 'alkautsar' ); ?></a> ›
			<a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>"><?php esc_html_e( 'Agenda', 'alkautsar' ); ?></a>
		</div>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container single-content">
		<?php while ( have_posts() ) : the_post();
			$e_date     = get_post_meta( get_the_ID(), 'alkautsar_event_date', true );
			$e_time     = get_post_meta( get_the_ID(), 'alkautsar_event_time', true );
			$e_end      = get_post_meta( get_the_ID(), 'alkautsar_event_end_time', true );
			$e_loc      = get_post_meta( get_the_ID(), 'alkautsar_event_location', true );
			$e_speaker  = get_post_meta( get_the_ID(), 'alkautsar_event_speaker', true );
			$e_cat      = get_post_meta( get_the_ID(), 'alkautsar_event_category', true );
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header" style="text-align:center; margin-bottom:2rem;">
					<?php if ( $e_cat ) : ?>
						<span class="event-card__cat event-card__cat--<?php echo esc_attr( $e_cat ); ?>" style="display:inline-block; margin-bottom:0.75rem;">
							<?php echo esc_html( alkautsar_event_category_label( $e_cat ) ); ?>
						</span>
					<?php endif; ?>
					<h1 class="entry-title" style="margin:0.5rem auto 1rem; max-width: 800px;"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail" style="margin: 0 auto 2rem; max-width: 1000px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</figure>
				<?php endif; ?>

				<!-- Event detail card -->
				<aside class="event-detail-card" style="max-width:760px; margin: 0 auto 2rem; background: var(--white); border: 1px solid var(--line); border-left: 4px solid var(--accent); border-radius: var(--radius-md); padding: 1.5rem; box-shadow: var(--shadow-sm);">
					<h2 style="margin-top:0; font-size:1.125rem; color: var(--secondary);"><?php esc_html_e( 'Detail Kegiatan', 'alkautsar' ); ?></h2>
					<ul style="list-style:none; padding:0; margin:0; display:grid; gap:0.875rem;">
						<?php if ( $e_date ) : ?>
							<li style="display:flex; gap:0.625rem; align-items:center;">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-deep)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
								<span><strong><?php esc_html_e( 'Tanggal:', 'alkautsar' ); ?></strong> <?php echo esc_html( alkautsar_format_event_date( $e_date ) ); ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $e_time ) : ?>
							<li style="display:flex; gap:0.625rem; align-items:center;">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-deep)" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
								<span><strong><?php esc_html_e( 'Waktu:', 'alkautsar' ); ?></strong> <?php echo esc_html( $e_time . ( $e_end ? ' – ' . $e_end : '' ) . ' WIB' ); ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $e_loc ) : ?>
							<li style="display:flex; gap:0.625rem; align-items:center;">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-deep)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
								<span><strong><?php esc_html_e( 'Lokasi:', 'alkautsar' ); ?></strong> <?php echo esc_html( $e_loc ); ?></span>
							</li>
						<?php endif; ?>
						<?php if ( $e_speaker ) : ?>
							<li style="display:flex; gap:0.625rem; align-items:center;">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--accent-deep)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
								<span><strong><?php esc_html_e( 'Pembicara:', 'alkautsar' ); ?></strong> <?php echo esc_html( $e_speaker ); ?></span>
							</li>
						<?php endif; ?>
					</ul>

					<?php
					$wa_link = alkautsar_whatsapp_link( 'Assalamualaikum, saya ingin bertanya tentang kegiatan "' . get_the_title() . '" (' . home_url( '/?p=' . get_the_ID() ) . ').' );
					?>
					<p style="margin: 1.25rem 0 0;">
						<a href="<?php echo esc_url( $wa_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp btn--sm">
							<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
							<?php esc_html_e( 'Tanya via WhatsApp', 'alkautsar' ); ?>
						</a>
					</p>
				</aside>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'alkautsar' ),
						'after'  => '</div>',
					) );
					?>
				</div>

				<footer class="entry-footer" style="max-width:760px; margin: 2rem auto 0; padding-top: 1.5rem; border-top: 1px solid var(--line); text-align:center;">
					<a href="<?php echo esc_url( home_url( '/kegiatan' ) ); ?>">← <?php esc_html_e( 'Kembali ke Agenda', 'alkautsar' ); ?></a>
				</footer>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
