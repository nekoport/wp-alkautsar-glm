<?php
/**
 * 404 template.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="container error-404">
		<div class="error-404__num">404</div>
		<h1><?php esc_html_e( 'Halaman Tidak Ditemukan', 'alkautsar' ); ?></h1>
		<p><?php esc_html_e( 'Maaf, halaman yang Anda cari mungkin telah dipindahkan atau tidak pernah ada. Silakan kembali ke beranda atau gunakan menu navigasi.', 'alkautsar' ); ?></p>
		<p style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap; margin-top:2rem;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Kembali ke Beranda', 'alkautsar' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/kontak' ) ); ?>" class="btn btn--ghost"><?php esc_html_e( 'Hubungi Kami', 'alkautsar' ); ?></a>
		</p>
	</div>
</main>

<?php
get_footer();
