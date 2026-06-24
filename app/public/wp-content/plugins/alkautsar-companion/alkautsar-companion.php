<?php
/**
 * Plugin Name: Al-Kautsar Companion
 * Plugin URI: https://example.com/alkautsar-companion
 * Description: Fitur tambahan untuk tema Masjid Al-Kautsar: backup reminder, demo content installer, custom widgets, dan tools pengelolaan masjid.
 * Version: 1.0.0
 * Author: Al-Kautsar Dev Team
 * License: GPL v2 or later
 * Text Domain: alkautsar-companion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ALKAUTSAR_COMPANION_VERSION', '1.0.0' );
define( 'ALKAUTSAR_COMPANION_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALKAUTSAR_COMPANION_URI', plugin_dir_url( __FILE__ ) );

/**
 * Include modules.
 */
require ALKAUTSAR_COMPANION_DIR . 'inc/dashboard-widget.php';
require ALKAUTSAR_COMPANION_DIR . 'inc/demo-installer.php';
require ALKAUTSAR_COMPANION_DIR . 'inc/admin-notices.php';

/**
 * Activation hook — set default options.
 */
function alkautsar_companion_activate() {
	// Track installation date.
	if ( ! get_option( 'alkautsar_companion_installed' ) ) {
		update_option( 'alkautsar_companion_installed', current_time( 'mysql' ) );
	}
}
register_activation_hook( __FILE__, 'alkautsar_companion_activate' );

/**
 * Add settings link on plugin page.
 */
function alkautsar_companion_action_links( $links ) {
	$settings_link = '<a href="admin.php?page=alkautsar-companion">' . __( 'Pengaturan', 'alkautsar-companion' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'alkautsar_companion_action_links' );

/**
 * Main admin page.
 */
function alkautsar_companion_admin_page() {
	?>
	<div class="wrap">
		<h1>Al-Kautsar Companion</h1>
		<p>Plugin pendamping untuk tema Masjid Al-Kautsar. Berikut fitur yang tersedia:</p>

		<div class="card" style="max-width:320px; display:inline-block; vertical-align:top; margin-right:20px;">
			<h3>Dashboard Widget</h3>
			<p>Widget di halaman utama admin yang menampilkan statistik cepat (jumlah berita, kegiatan, donatur) dan reminder backup.</p>
		</div>

		<div class="card" style="max-width:320px; display:inline-block; vertical-align:top; margin-right:20px;">
			<h3>Demo Content Installer</h3>
			<p>Klik satu tombol untuk install contoh berita, kegiatan, dan program. Cocok untuk pengurus baru yang ingin lihat contoh konten.</p>
			<p>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=alkautsar-companion-demo' ) ); ?>" class="button button-primary">
					Install Demo Content
				</a>
			</p>
		</div>

		<div class="card" style="max-width:320px; display:inline-block; vertical-align:top;">
			<h3>Notifikasi Pintar</h3>
			<p>Notifikasi otomatis di dashboard: pengingat backup, info plugin yang perlu diupdate, dan tips pengelolaan.</p>
		</div>
	</div>
	<?php
}

function alkautsar_companion_admin_menu() {
	add_menu_page(
		'Al-Kautsar Companion',
		'Al-Kautsar',
		'manage_options',
		'alkautsar-companion',
		'alkautsar_companion_admin_page',
		'dashicons-superhero-alt',
		4
	);
}
add_action( 'admin_menu', 'alkautsar_companion_admin_menu' );
