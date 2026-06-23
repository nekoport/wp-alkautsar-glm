<?php
/**
 * Custom User Roles for Masjid Al-Kautsar.
 *
 * 3 Role:
 *   - Humas (clone of Administrator) — full access
 *   - Bendahara — kelola donasi + keuangan + transparansi
 *   - Editor — kelola artikel, program, agenda, galeri, dll
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom roles on theme activation.
 */
function alkautsar_register_user_roles() {
	// Remove existing if any (for re-registration).
	remove_role( 'humas' );
	remove_role( 'bendahara' );
	remove_role( 'editor_masjid' );

	// ─── Role: Humas (Superadmin) ──────────────────────────
	// Clone semua capability Administrator.
	$admin_caps = get_role( 'administrator' )->capabilities;
	add_role( 'humas', __( 'Humas (Superadmin)', 'alkautsar' ), $admin_caps );

	// ─── Role: Bendahara ────────────────────────────────────
	// Akses: Laporan Keuangan, Beranda (Transparansi), Pengaturan Donasi.
	add_role( 'bendahara', __( 'Bendahara', 'alkautsar' ), array(
		'read'                   => true,
		'upload_files'           => true,
		'edit_posts'             => true,
		'edit_others_posts'      => true,
		'edit_published_posts'   => true,
		'publish_posts'          => true,
		'delete_posts'           => true,
		'delete_others_posts'    => true,
		'delete_published_posts' => true,
		'manage_categories'      => true,
		// Custom: financial reports + donation settings.
		'edit_financial_report'         => true,
		'edit_financial_reports'        => true,
		'edit_others_financial_reports' => true,
		'publish_financial_reports'     => true,
		'delete_financial_report'       => true,
		'delete_financial_reports'      => true,
		'delete_others_financial_reports' => true,
		'edit_published_financial_reports' => true,
		// Theme mods access (for Beranda Transparansi & Pengaturan Donasi).
		'edit_theme_options'     => true,
	) );

	// ─── Role: Editor Masjid ────────────────────────────────
	// Akses: Posts, Program, Kegiatan, Pengurus DKM, Galeri, Beranda (Hero, Tentang).
	add_role( 'editor_masjid', __( 'Editor Masjid', 'alkautsar' ), array(
		'read'                   => true,
		'upload_files'           => true,
		'edit_posts'             => true,
		'edit_others_posts'      => true,
		'edit_published_posts'   => true,
		'publish_posts'          => true,
		'delete_posts'           => true,
		'delete_others_posts'    => true,
		'delete_published_posts' => true,
		'manage_categories'      => true,
		'edit_pages'             => true,
		'edit_others_pages'      => true,
		'edit_published_pages'   => true,
		'publish_pages'          => true,
		'delete_pages'           => true,
		'delete_others_pages'    => true,
		'delete_published_pages' => true,
		// Theme mods access (for Beranda Hero, Tentang, Profil).
		'edit_theme_options'     => true,
	) );
}
add_action( 'after_switch_theme', 'alkautsar_register_user_roles' );

// Also register on init if not yet registered (for first install).
add_action( 'init', function() {
	if ( ! get_role( 'humas' ) ) {
		alkautsar_register_user_roles();
	}
});

/**
 * Map custom CPT capabilities to roles.
 */
function alkautsar_map_cpt_capabilities() {
	// Financial Report — accessible to: administrator, humas, bendahara.
	$finance_caps = array(
		'edit_financial_report',
		'edit_financial_reports',
		'edit_others_financial_reports',
		'publish_financial_reports',
		'delete_financial_report',
		'delete_financial_reports',
		'delete_others_financial_reports',
		'edit_published_financial_reports',
		'read_financial_report',
	);

	foreach ( array( 'administrator', 'humas', 'bendahara' ) as $role_name ) {
		$role = get_role( $role_name );
		if ( $role ) {
			foreach ( $finance_caps as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}

	// Event, Program, DKM, Galeri — accessible to: administrator, humas, editor_masjid.
	$content_caps = array();
	foreach ( array( 'event', 'program', 'dkm_member', 'galeri' ) as $cpt ) {
		$content_caps[] = "edit_{$cpt}";
		$content_caps[] = "edit_{$cpt}s";
		$content_caps[] = "edit_others_{$cpt}s";
		$content_caps[] = "publish_{$cpt}s";
		$content_caps[] = "delete_{$cpt}";
		$content_caps[] = "delete_{$cpt}s";
		$content_caps[] = "delete_others_{$cpt}s";
		$content_caps[] = "edit_published_{$cpt}s";
		$content_caps[] = "read_{$cpt}";
	}

	foreach ( array( 'administrator', 'humas', 'editor_masjid', 'bendahara' ) as $role_name ) {
		// Bendahara also gets event/program access (for transparency reports),
		// but main content role is editor_masjid.
		$role = get_role( $role_name );
		if ( $role ) {
			foreach ( $content_caps as $cap ) {
				$role->add_cap( $cap );
			}
		}
	}
}
add_action( 'init', 'alkautsar_map_cpt_capabilities', 20 );

/**
 * Hide menus based on user role.
 */
function alkautsar_role_based_menu_hiding() {
	$user = wp_get_current_user();
	if ( ! $user || ! $user->ID ) { return; }

	$roles = (array) $user->roles;

	// If user is administrator OR humas — show everything.
	if ( in_array( 'administrator', $roles, true ) || in_array( 'humas', $roles, true ) ) {
		return;
	}

	// Define which menus each role can access.
	$role_menus = array(
		'bendahara' => array(
			// CPT menus.
			'edit.php?post_type=financial_report',
			// Admin settings pages (slugs).
			'alkautsar-transparency-settings',
			'alkautsar-donation-settings',
			// Standard.
			'upload.php',
			'index.php',
			'profile.php',
		),
		'editor_masjid' => array(
			// CPT menus.
			'edit.php',
			'edit.php?post_type=event',
			'edit.php?post_type=program',
			'edit.php?post_type=dkm_member',
			'edit.php?post_type=galeri',
			// Admin settings pages.
			'alkautsar-hero-settings',
			'alkautsar-about-settings',
			'alkautsar-profile-settings',
			// Standard.
			'upload.php',
			'index.php',
			'profile.php',
		),
	);

	// Determine which menus to KEEP based on user's roles.
	$allowed_menus = array();
	foreach ( $roles as $role ) {
		if ( isset( $role_menus[ $role ] ) ) {
			$allowed_menus = array_merge( $allowed_menus, $role_menus[ $role ] );
		}
	}
	$allowed_menus = array_unique( $allowed_menus );

	if ( empty( $allowed_menus ) ) { return; }

	// List of ALL custom admin menus we want to potentially hide.
	$our_admin_pages = array(
		'alkautsar-hero-settings',
		'alkautsar-about-settings',
		'alkautsar-transparency-settings',
		'alkautsar-profile-settings',
		'alkautsar-donation-settings',
		'alkautsar-contact-settings',
		'alkautsar-finance-settings',
		'alkautsar-guide',
		'alkautsar-companion',
	);

	global $menu, $submenu;
	if ( ! isset( $menu ) || ! is_array( $menu ) ) { return; }

	// Hide menus that user can't access.
	foreach ( $menu as $key => $item ) {
		$slug = $item[2];
		// Allow standard WP menus if in allowed list.
		if ( in_array( $slug, $allowed_menus, true ) ) {
			continue;
		}
		// Hide our custom menus that user doesn't have access to.
		if ( in_array( $slug, $our_admin_pages, true ) && ! in_array( $slug, $allowed_menus, true ) ) {
			unset( $menu[ $key ] );
		}
		// Hide CPT menus user doesn't have access to.
		if ( 0 === strpos( $slug, 'edit.php?post_type=' ) && ! in_array( $slug, $allowed_menus, true ) ) {
			unset( $menu[ $key ] );
		}
		// Hide Tools, Settings, Plugins, Themes, Users for non-admin/humas.
		if ( in_array( $slug, array( 'tools.php', 'options-general.php', 'plugins.php', 'themes.php', 'users.php', 'edit-comments.php' ), true ) ) {
			unset( $menu[ $key ] );
		}
	}
}
add_action( 'admin_menu', 'alkautsar_role_based_menu_hiding', 999 );

/**
 * Hide admin bar "New" dropdown items user can't access.
 */
function alkautsar_role_based_admin_bar( $wp_admin_bar ) {
	$user = wp_get_current_user();
	if ( ! $user || ! $user->ID ) { return; }
	$roles = (array) $user->roles;

	if ( in_array( 'administrator', $roles, true ) || in_array( 'humas', $roles, true ) ) {
		return;
	}

	// Remove "New > ..." items for CPTs user can't access.
	if ( ! in_array( 'bendahara', $roles, true ) ) {
		$wp_admin_bar->remove_node( 'new-financial_report' );
	}
	if ( ! in_array( 'editor_masjid', $roles, true ) ) {
		$wp_admin_bar->remove_node( 'new-event' );
		$wp_admin_bar->remove_node( 'new-program' );
		$wp_admin_bar->remove_node( 'new-dkm_member' );
		$wp_admin_bar->remove_node( 'new-galeri' );
	}
}
add_action( 'admin_bar_menu', 'alkautsar_role_based_admin_bar', 999 );

/**
 * Add user guide info on user profile page.
 */
function alkautsar_user_role_help( $user ) {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	?>
	<h3><?php esc_html_e( 'Panduan Role Masjid Al-Kautsar', 'alkautsar' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><?php esc_html_e( 'Informasi Role', 'alkautsar' ); ?></th>
			<td>
				<p style="font-size:13px; line-height:1.7;">
					<strong>Humas (Superadmin):</strong> Akses penuh ke semua menu & pengaturan.<br>
					<strong>Bendahara:</strong> Kelola Laporan Keuangan, Beranda (Transparansi), Pengaturan Donasi.<br>
					<strong>Editor Masjid:</strong> Kelola Berita, Program, Kegiatan, Pengurus DKM, Galeri Foto, Beranda (Hero & Tentang), Profil Masjid.<br>
					<strong>Administrator:</strong> WordPress default (semua akses, termasuk theme & plugin).
				</p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'alkautsar_user_role_help' );
add_action( 'edit_user_profile', 'alkautsar_user_role_help' );
