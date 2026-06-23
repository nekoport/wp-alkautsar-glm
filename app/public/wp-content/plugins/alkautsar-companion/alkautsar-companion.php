<?php
/**
 * Plugin Name: Al-Kautsar Companion
 * Plugin URI: https://example.com/alkautsar-companion
 * Description: Fitur tambahan untuk tema Masjid Al-Kautsar: demo content installer, dashboard widget, dan tools pengelolaan masjid.
 * Version: 1.0.2
 * Author: Al-Kautsar Dev Team
 * License: GPL v2 or later
 * Text Domain: alkautsar-companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALKAUTSAR_COMPANION_VERSION', '1.0.2' );
define( 'ALKAUTSAR_COMPANION_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALKAUTSAR_COMPANION_URI', plugin_dir_url( __FILE__ ) );

/**
 * Include modules.
 */
require ALKAUTSAR_COMPANION_DIR . 'inc/dashboard-widget.php';
require ALKAUTSAR_COMPANION_DIR . 'inc/demo-installer.php';
require ALKAUTSAR_COMPANION_DIR . 'inc/admin-notices.php';

/**
 * Activation hook — set default options & clear menu cache.
 */
function alkautsar_companion_activate() {
	if ( ! get_option( 'alkautsar_companion_installed' ) ) {
		update_option( 'alkautsar_companion_installed', current_time( 'mysql' ) );
	}
	// Force-clear menu transient cache (fix "Sorry, you are not allowed to access this page").
	delete_transient( 'alkautsar_companion_menu_cache' );
	// Flush rewrite rules.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'alkautsar_companion_activate' );

/**
 * Deactivation hook — cleanup.
 */
function alkautsar_companion_deactivate() {
	delete_transient( 'alkautsar_companion_menu_cache' );
}
register_deactivation_hook( __FILE__, 'alkautsar_companion_deactivate' );

/**
 * Add settings link on plugin page.
 */
function alkautsar_companion_action_links( $links ) {
	$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=alkautsar-companion' ) ) . '">' . __( 'Buka', 'alkautsar-companion' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'alkautsar_companion_action_links' );

/**
 * Main admin page (single page — no submenu untuk hindari capability conflict).
 */
function alkautsar_companion_admin_page() {
	// Fallback safety check — kalau capability menu gagal, check manual di sini.
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Anda tidak punya akses ke halaman ini. Silakan login sebagai Editor atau Administrator.', 'alkautsar-companion' ) );
	}

	$installed = isset( $_GET['installed'] ) ? sanitize_text_field( wp_unslash( $_GET['installed'] ) ) : '';
	$error     = isset( $_GET['error'] ) ? sanitize_text_field( wp_unslash( $_GET['error'] ) ) : '';
	?>
	<div class="wrap">
		<h1>Al-Kautsar Companion</h1>
		<p>Plugin pendamping untuk tema Masjid Al-Kautsar. Versi <?php echo esc_html( ALKAUTSAR_COMPANION_VERSION ); ?>.</p>

		<?php if ( '1' === $installed ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong>Berhasil!</strong> Demo content berhasil diinstall. Silakan lihat menu <strong>Berita</strong>, <strong>Kegiatan</strong>, <strong>Program</strong>, <strong>Laporan Keuangan</strong>, dan <strong>Penerima Manfaat</strong> di sidebar.</p>
			</div>
		<?php endif; ?>

		<?php if ( '1' === $error ) : ?>
			<div class="notice notice-error is-dismissible">
				<p><strong>Gagal install.</strong> Terjadi kesalahan saat install demo content. Coba lagi atau hubungi developer.</p>
			</div>
		<?php endif; ?>

		<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px; margin-top:24px;">

			<div class="card" style="max-width:none;">
				<h3 class="dashicons-before dashicons-download"> Demo Content Installer</h3>
				<p>Klik satu tombol untuk install contoh berita, kegiatan, program, laporan keuangan, dan penerima manfaat. Cocok untuk pengurus baru yang ingin lihat contoh konten.</p>
				<ul style="list-style:disc; padding-left:20px; font-size:14px;">
					<li>5 Berita &amp; Informasi</li>
					<li>4 Kegiatan Mendatang</li>
					<li>6 Program</li>
					<li>4 Laporan Keuangan</li>
					<li>4 Penerima Manfaat</li>
				</ul>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Install demo content sekarang? Aman, tidak akan menimpa konten yang sudah ada.');">
					<?php wp_nonce_field( 'alkautsar_install_demo' ); ?>
					<input type="hidden" name="action" value="alkautsar_install_demo">
					<p>
						<button type="submit" class="button button-primary button-hero">
							<span class="dashicons dashicons-download" style="vertical-align:middle; margin-right:6px;"></span>
							Install Demo Content Sekarang
						</button>
					</p>
				</form>
				<p style="font-size:12px; color:#666; margin-top:8px;">
					Catatan: Aman diinstall. Tidak akan menimpa konten yang sudah ada. Anda bisa edit/hapus setelah install.
				</p>
			</div>

			<div class="card" style="max-width:none;">
				<h3 class="dashicons-before dashicons-dashboard"> Dashboard Widget</h3>
				<p>Widget di halaman utama admin yang menampilkan statistik cepat (jumlah berita, kegiatan, donatur) dan reminder backup mingguan.</p>
				<p>Lihat di: <a href="<?php echo esc_url( admin_url( 'index.php' ) ); ?>">Dashboard &rarr;</a></p>
			</div>

			<div class="card" style="max-width:none;">
				<h3 class="dashicons-before dashicons-book-alt"> Panduan Visual</h3>
				<p>Tutorial lengkap cara posting berita, kegiatan, program, dan pengaturan masjid.</p>
				<p><a href="<?php echo esc_url( admin_url( 'admin.php?page=alkautsar-guide' ) ); ?>" class="button button-secondary">Buka Panduan &rarr;</a></p>
			</div>

			<div class="card" style="max-width:none;">
				<h3 class="dashicons-before dashicons-backup"> Backup Website</h3>
				<p>Backup otomatis = asuransi. Install plugin UpdraftPlus (gratis) dan setup backup mingguan ke Google Drive.</p>
				<p><a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=updraftplus&tab=search&type=term' ) ); ?>" class="button button-secondary">Install UpdraftPlus &rarr;</a></p>
			</div>

		</div>

		<div style="margin-top:30px; padding:16px; background:#F1E7D2; border-left:4px solid #D4AF37; border-radius:4px;">
			<h4 style="margin-top:0; color:#3B1E12;">Status Plugin</h4>
			<p style="margin-bottom:0;">
				<strong>Versi:</strong> <?php echo esc_html( ALKAUTSAR_COMPANION_VERSION ); ?><br>
				<strong>Tema aktif:</strong> <?php echo esc_html( wp_get_theme()->get( 'Name' ) . ' ' . wp_get_theme()->get( 'Version' ) ); ?><br>
				<strong>WordPress:</strong> <?php echo esc_html( get_bloginfo( 'version' ) ); ?><br>
				<strong>User Anda:</strong> <?php echo esc_html( wp_get_current_user()->display_name . ' (' . ( current_user_can( 'manage_options' ) ? 'Administrator' : ( current_user_can( 'edit_posts' ) ? 'Editor' : 'Lainnya' ) ) . ')' ); ?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Register single admin menu (no submenu — avoid capability issues).
 */
function alkautsar_companion_admin_menu() {
	// Capability: 'edit_posts' = Editor & Admin bisa akses.
	// Kalau mau admin-only, ubah ke 'manage_options'.
	$cap = 'edit_posts';

	add_menu_page(
		'Al-Kautsar Companion',
		'Al-Kautsar',
		$cap,
		'alkautsar-companion',
		'alkautsar_companion_admin_page',
		'dashicons-superhero-alt',
		3
	);
}
add_action( 'admin_menu', 'alkautsar_companion_admin_menu' );

/**
 * Network admin menu (multisite support).
 */
function alkautsar_companion_network_admin_menu() {
	add_menu_page(
		'Al-Kautsar Companion',
		'Al-Kautsar',
		'manage_network_options',
		'alkautsar-companion',
		'alkautsar_companion_admin_page',
		'dashicons-superhero-alt',
		3
	);
}
add_action( 'network_admin_menu', 'alkautsar_companion_network_admin_menu' );
