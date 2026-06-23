<?php
/**
 * Admin notices — pengingat backup, info update, tips.
 *
 * @package AlKautsarCompanion
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Notifikasi sekali setelah aktivasi.
 */
function alkautsar_companion_welcome_notice() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        if ( 'yes' === get_option( 'alkautsar_companion_welcome_dismissed' ) ) { return; }

        ?>
        <div class="notice notice-success is-dismissible alkautsar-welcome-notice">
                <p>
                        <strong>Al-Kautsar Companion aktif!</strong>
                        Klik menu <a href="<?php echo esc_url( admin_url( 'admin.php?page=alkautsar-companion-demo' ) ); ?>"><strong>Install Demo Content</strong></a>
                        di sidebar untuk install contoh berita, kegiatan, dan program sekaligus.
                        Atau baca <a href="<?php echo esc_url( admin_url( 'admin.php?page=alkautsar-guide' ) ); ?>"><strong>Panduan Visual</strong></a> untuk tutorial lengkap.
                </p>
        </div>
        <script>
        jQuery(function($) {
                $(document).on('click', '.alkautsar-welcome-notice .notice-dismiss', function() {
                        $.post(ajaxurl, {
                                action: 'alkautsar_dismiss_welcome',
                                nonce: '<?php echo esc_js( wp_create_nonce( 'alkautsar_dismiss_welcome' ) ); ?>'
                        });
                });
        });
        </script>
        <?php
}
add_action( 'admin_notices', 'alkautsar_companion_welcome_notice' );

function alkautsar_companion_dismiss_welcome() {
        check_ajax_referer( 'alkautsar_dismiss_welcome', 'nonce' );
        if ( current_user_can( 'manage_options' ) ) {
                update_option( 'alkautsar_companion_welcome_dismissed', 'yes' );
        }
        wp_die();
}
add_action( 'wp_ajax_alkautsar_dismiss_welcome', 'alkautsar_companion_dismiss_welcome' );

/**
 * Pengingat backup mingguan.
 */
function alkautsar_companion_backup_reminder() {
        if ( ! current_user_can( 'manage_options' ) ) { return; }
        $last = get_option( 'alkautsar_companion_last_backup_check', 0 );
        if ( time() - $last < 14 * DAY_IN_SECONDS ) { return; }

        // Cek apakah plugin backup terinstall.
        if ( ! function_exists( 'is_plugin_active' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $has_backup_plugin = false;
        if ( function_exists( 'is_plugin_active' ) && ( is_plugin_active( 'updraftplus/updraftplus.php' ) || is_plugin_active( 'wpvivid-backuprestore/wpvivid-backuprestore.php' ) || is_plugin_active( 'backwpup/backwpup.php' ) ) ) {
                $has_backup_plugin = true;
        }

        if ( ! $has_backup_plugin ) {
                ?>
                <div class="notice notice-warning is-dismissible">
                        <p>
                                <strong>Backup Website Belum Aktif!</strong>
                                Kami belum mendeteksi plugin backup. Sangat disarankan install <strong>UpdraftPlus</strong> (gratis)
                                dan setup backup otomatis mingguan ke Google Drive.
                                <a href="<?php echo esc_url( admin_url( 'plugin-install.php?s=updraftplus&tab=search&type=term' ) ); ?>">Install sekarang &rarr;</a>
                        </p>
                </div>
                <?php
        }

        update_option( 'alkautsar_companion_last_backup_check', time() );
}
add_action( 'admin_notices', 'alkautsar_companion_backup_reminder' );
