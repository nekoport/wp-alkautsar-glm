<?php
/**
 * Template Name: Kontak
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$address   = get_theme_mod( 'alkautsar_address', 'Jl. Masjid Al-Kautsar No. 1, Jakarta' );
$phone     = get_theme_mod( 'alkautsar_phone', '(021) 1234 5678' );
$email     = get_theme_mod( 'alkautsar_email', 'info@masjidal-kautsar.id' );
$ig        = get_theme_mod( 'alkautsar_instagram', '' );
$yt        = get_theme_mod( 'alkautsar_youtube', '' );
$fb        = get_theme_mod( 'alkautsar_facebook', '' );
$tt        = get_theme_mod( 'alkautsar_tiktok', '' );
$tg        = get_theme_mod( 'alkautsar_telegram', '' );
$map_lat   = get_theme_mod( 'alkautsar_map_lat', '-6.2088' );
$map_lng   = get_theme_mod( 'alkautsar_map_lng', '106.8456' );
// Sanitize numeric for safety.
$map_lat = is_numeric( $map_lat ) ? $map_lat : '-6.2088';
$map_lng = is_numeric( $map_lng ) ? $map_lng : '106.8456';
// OpenStreetMap embed URL — no API key needed.
$map_embed = sprintf(
	'https://www.openstreetmap.org/export/embed.html?bbox=%s%%2C%s%%2C%s%%2C%s&layer=mapnik&marker=%s%%2C%s',
	floatval( $map_lng ) - 0.01,
	floatval( $map_lat ) - 0.01,
	floatval( $map_lng ) + 0.01,
	floatval( $map_lat ) + 0.01,
	$map_lat,
	$map_lng
);
$map_link = sprintf( 'https://www.openstreetmap.org/?mlat=%s&mlon=%s#map=17/%s/%s', $map_lat, $map_lng, $map_lat, $map_lng );
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Kontak & Lokasi', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Hubungi kami atau kunjungi langsung Masjid Al-Kautsar.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">

		<div class="contact-page__grid">
			<!-- Contact info -->
			<div class="contact-page__info">
				<h2 class="section-title" style="margin-bottom:1.5rem; font-size:1.75rem;"><?php esc_html_e( 'Informasi Kontak', 'alkautsar' ); ?></h2>
				<ul class="contact__list">
					<li>
						<span class="contact__icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
						</span>
						<div>
							<p class="contact__label"><?php esc_html_e( 'Alamat', 'alkautsar' ); ?></p>
							<p class="contact__value"><?php alkautsar_address(); ?></p>
							<p style="margin-top:0.5rem;"><a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener noreferrer" style="font-size:0.875rem; color: var(--accent-deep); font-weight:600;"><?php esc_html_e( 'Buka di peta →', 'alkautsar' ); ?></a></p>
						</div>
					</li>
					<li>
						<span class="contact__icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						</span>
						<div>
							<p class="contact__label"><?php esc_html_e( 'Telepon', 'alkautsar' ); ?></p>
							<p class="contact__value"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
						</div>
					</li>
					<li>
						<span class="contact__icon">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						</span>
						<div>
							<p class="contact__label"><?php esc_html_e( 'Email', 'alkautsar' ); ?></p>
							<p class="contact__value"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
						</div>
					</li>
					<li>
						<span class="contact__icon contact__icon--wa">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
						</span>
						<div>
							<p class="contact__label"><?php esc_html_e( 'WhatsApp', 'alkautsar' ); ?></p>
							<p class="contact__value">
								<a href="<?php echo esc_url( alkautsar_whatsapp_link() ); ?>" target="_blank" rel="noopener noreferrer">
									<?php echo esc_html( '+' . preg_replace( '/[^0-9]/', '', get_theme_mod( 'alkautsar_whatsapp', '6281234567890' ) ) ); ?>
								</a>
							</p>
						</div>
					</li>
				</ul>

				<!-- Social Media -->
				<h2 class="section-title" style="margin:2.5rem 0 1.25rem; font-size:1.75rem;"><?php esc_html_e( 'Media Sosial', 'alkautsar' ); ?></h2>
				<div class="contact-social">
					<?php if ( $ig ) : ?>
						<a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener noreferrer" class="contact-social__link" aria-label="Instagram">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
							<span>Instagram</span>
						</a>
					<?php endif; ?>
					<?php if ( $fb ) : ?>
						<a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" class="contact-social__link" aria-label="Facebook">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987H7.898v-2.89h2.54V9.797c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
							<span>Facebook</span>
						</a>
					<?php endif; ?>
					<?php if ( $yt ) : ?>
						<a href="<?php echo esc_url( $yt ); ?>" target="_blank" rel="noopener noreferrer" class="contact-social__link" aria-label="YouTube">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
							<span>YouTube</span>
						</a>
					<?php endif; ?>
					<?php if ( $tt ) : ?>
						<a href="<?php echo esc_url( $tt ); ?>" target="_blank" rel="noopener noreferrer" class="contact-social__link" aria-label="TikTok">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
							<span>TikTok</span>
						</a>
					<?php endif; ?>
					<?php if ( $tg ) : ?>
						<a href="<?php echo esc_url( $tg ); ?>" target="_blank" rel="noopener noreferrer" class="contact-social__link" aria-label="Telegram">
							<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
							<span>Telegram</span>
						</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- Map -->
			<div class="contact-page__map">
				<h2 class="section-title" style="margin-bottom:1.5rem; font-size:1.75rem;"><?php esc_html_e( 'Lokasi Masjid', 'alkautsar' ); ?></h2>
				<div class="contact-map__embed">
					<iframe
						src="<?php echo esc_url( $map_embed ); ?>"
						style="width:100%; height:480px; border:0; border-radius: var(--radius-md); box-shadow: var(--shadow-md);"
						loading="lazy"
						title="<?php esc_attr_e( 'Peta Lokasi Masjid Al-Kautsar', 'alkautsar' ); ?>"
						referrerpolicy="no-referrer-when-downgrade">
					</iframe>
				</div>
				<p style="text-align:center; margin-top:1rem;">
					<a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn btn--ghost btn--sm">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
						<?php esc_html_e( 'Buka Peta Fullscreen', 'alkautsar' ); ?>
					</a>
				</p>
			</div>
		</div>

	</div>
</main>

<?php get_footer(); ?>
