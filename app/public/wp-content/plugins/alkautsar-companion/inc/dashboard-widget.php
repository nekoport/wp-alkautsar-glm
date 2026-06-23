<?php
/**
 * Dashboard widget — tampilkan statistik cepat di halaman utama admin.
 *
 * @package AlKautsarCompanion
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function alkautsar_companion_dashboard_widget() {
	wp_add_dashboard_widget(
		'alkautsar_companion_stats',
		'Masjid Al-Kautsar — Statistik Cepat',
		'alkautsar_companion_dashboard_render'
	);
}
add_action( 'wp_dashboard_setup', 'alkautsar_companion_dashboard_widget' );

/**
 * Helper: safely get post count (return 0 if CPT doesn't exist).
 */
function alkautsar_companion_safe_count( $post_type ) {
	$post_type_obj = get_post_type_object( $post_type );
	if ( ! $post_type_obj ) {
		return 0;
	}
	$counts = wp_count_posts( $post_type );
	if ( ! $counts || ! isset( $counts->publish ) ) {
		return 0;
	}
	return (int) $counts->publish;
}

function alkautsar_companion_dashboard_render() {
	$posts_count    = alkautsar_companion_safe_count( 'post' );
	$events_count   = alkautsar_companion_safe_count( 'event' );
	$programs_count = alkautsar_companion_safe_count( 'program' );
	$galeri_count   = alkautsar_companion_safe_count( 'galeri' );
	$reports_count  = alkautsar_companion_safe_count( 'financial_report' );
	$dkm_count      = alkautsar_companion_safe_count( 'dkm_member' );

	?>
	<style>
		.alkautsar-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
		.alkautsar-stat { background:#F8F1E4; padding:12px; border-radius:8px; text-align:center; }
		.alkautsar-stat strong { display:block; font-size:24px; color:#3B1E12; font-family:Georgia, serif; }
		.alkautsar-stat span { font-size:11px; color:#6B4A38; }
		.alkautsar-backup-reminder { background:#fff4e5; border-left:4px solid #E07B00; padding:10px 14px; border-radius:4px; margin-top:12px; font-size:13px; }
		.alkautsar-backup-reminder strong { color:#B85C00; }
	</style>

	<div class="alkautsar-stats">
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $posts_count ); ?></strong>
			<span>Berita</span>
		</div>
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $events_count ); ?></strong>
			<span>Kegiatan</span>
		</div>
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $programs_count ); ?></strong>
			<span>Program</span>
		</div>
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $galeri_count ); ?></strong>
			<span>Album Galeri</span>
		</div>
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $dkm_count ); ?></strong>
			<span>Pengurus DKM</span>
		</div>
		<div class="alkautsar-stat">
			<strong><?php echo esc_html( $reports_count ); ?></strong>
			<span>Laporan Keuangan</span>
		</div>
	</div>

	<?php
	// Backup reminder.
	$last_backup = get_option( 'alkautsar_companion_last_backup_reminder', 0 );
	$days_since = ( time() - $last_backup ) / DAY_IN_SECONDS;

	if ( $days_since > 7 || 0 === $last_backup ) {
		?>
		<div class="alkautsar-backup-reminder">
			<strong>Reminder Backup:</strong> Sudah lebih dari 7 hari sejak reminder backup terakhir.
			Pastikan website di-backup via UpdraftPlus atau plugin backup favorit Anda.
			<a href="<?php echo esc_url( admin_url( 'plugins.php?s=updraftplus&plugin-search-input=Search+Plugins' ) ); ?>">Install UpdraftPlus &rarr;</a>
		</div>
		<?php
		update_option( 'alkautsar_companion_last_backup_reminder', time() );
	}
}
