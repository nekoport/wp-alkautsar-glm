<?php
/**
 * Admin Settings Pages — separate editable sections from Customizer.
 *
 * Uses custom form handler (NOT Settings API) to store values directly
 * as theme_mod. Avoids conflict with WordPress "Settings" page.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Register admin menu pages for settings.
 */
function alkautsar_settings_admin_menu() {
        add_menu_page(
                __( 'Profil & DKM', 'alkautsar' ),
                __( 'Profil & DKM', 'alkautsar' ),
                'edit_posts',
                'alkautsar-profile-settings',
                'alkautsar_profile_settings_page',
                'dashicons-building',
                4
        );

        add_menu_page(
                __( 'Keuangan Masjid', 'alkautsar' ),
                __( 'Keuangan Masjid', 'alkautsar' ),
                'edit_posts',
                'alkautsar-finance-settings',
                'alkautsar_finance_settings_page',
                'dashicons-chart-bar',
                5
        );

        add_menu_page(
                __( 'Pengaturan Donasi', 'alkautsar' ),
                __( 'Pengaturan Donasi', 'alkautsar' ),
                'edit_posts',
                'alkautsar-donation-settings',
                'alkautsar_donation_settings_page',
                'dashicons-money-alt',
                6
        );

        add_menu_page(
                __( 'Pengaturan Kontak', 'alkautsar' ),
                __( 'Pengaturan Kontak', 'alkautsar' ),
                'edit_posts',
                'alkautsar-contact-settings',
                'alkautsar_contact_settings_page',
                'dashicons-phone',
                7
        );

        add_menu_page(
                __( 'Beranda (Hero)', 'alkautsar' ),
                __( 'Beranda (Hero)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-hero-settings',
                'alkautsar_hero_settings_page',
                'dashicons-format-image',
                8
        );

        add_menu_page(
                __( 'Beranda (Tentang)', 'alkautsar' ),
                __( 'Beranda (Tentang)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-about-settings',
                'alkautsar_about_settings_page',
                'dashicons-info-outline',
                9
        );

        add_menu_page(
                __( 'Beranda (Transparansi)', 'alkautsar' ),
                __( 'Beranda (Transparansi)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-transparency-settings',
                'alkautsar_transparency_settings_page',
                'dashicons-visibility',
                10
        );
}
add_action( 'admin_menu', 'alkautsar_settings_admin_menu' );

/**
 * Handle form submission — store as theme_mod directly.
 * TIDAK pakai register_setting() / Settings API (yang bocor ke halaman Settings generik).
 */
function alkautsar_handle_settings_save() {
        if ( ! current_user_can( 'edit_posts' ) ) {
                wp_die( esc_html__( 'Anda tidak punya izin untuk menyimpan pengaturan.', 'alkautsar' ) );
        }

        // Verify nonce.
        if ( ! isset( $_POST['alkautsar_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_settings_nonce'] ) ), 'alkautsar_save_settings' ) ) {
                wp_die( esc_html__( 'Sesi kedaluwarsa. Silakan coba lagi.', 'alkautsar' ) );
        }

        // Determine which page submitted the form.
        $page = isset( $_POST['alkautsar_settings_page'] ) ? sanitize_text_field( wp_unslash( $_POST['alkautsar_settings_page'] ) ) : '';

        // Define field config per page.
        $fields_config = alkautsar_get_settings_fields();
        if ( ! isset( $fields_config[ $page ] ) ) {
                wp_die( esc_html__( 'Halaman tidak valid.', 'alkautsar' ) );
        }

        // Save each field as theme_mod.
        foreach ( $fields_config[ $page ] as $field_id => $sanitize ) {
                if ( isset( $_POST[ $field_id ] ) ) {
                        $value = wp_unslash( $_POST[ $field_id ] );
                        if ( 'sanitize_textarea_field' === $sanitize ) {
                                $value = sanitize_textarea_field( $value );
                        } elseif ( 'wp_kses_post' === $sanitize ) {
                                $value = wp_kses_post( $value );
                        } elseif ( 'sanitize_email' === $sanitize ) {
                                $value = sanitize_email( $value );
                        } elseif ( 'esc_url_raw' === $sanitize ) {
                                $value = esc_url_raw( $value );
                        } else {
                                $value = sanitize_text_field( $value );
                        }
                        set_theme_mod( $field_id, $value );
                } else {
                        // Checkbox or unchecked — clear.
                        set_theme_mod( $field_id, '' );
                }
        }

        // Redirect back with success message.
        $redirect = add_query_arg( 'settings-updated', '1', admin_url( 'admin.php?page=' . $page ) );
        wp_safe_redirect( $redirect );
        exit;
}
add_action( 'admin_post_alkautsar_save_settings', 'alkautsar_handle_settings_save' );

/**
 * Get field config per settings page.
 * Format: page_slug => array( field_id => sanitize_callback )
 */
function alkautsar_get_settings_fields() {
        return array(
                'alkautsar-profile-settings' => array(
                        'alkautsar_profile_history' => 'wp_kses_post',
                        'alkautsar_vision'          => 'sanitize_textarea_field',
                        'alkautsar_mission'         => 'sanitize_textarea_field',
                        'alkautsar_dkm_chairman'    => 'sanitize_text_field',
                        'alkautsar_dkm_secretary'   => 'sanitize_text_field',
                        'alkautsar_dkm_treasurer'   => 'sanitize_text_field',
                        'alkautsar_dkm_imam'        => 'sanitize_text_field',
                        'alkautsar_dkm_extra'       => 'wp_kses_post',
                ),
                'alkautsar-finance-settings' => array(
                        'alkautsar_finance_total_income'  => 'sanitize_text_field',
                        'alkautsar_finance_total_expense' => 'sanitize_text_field',
                        'alkautsar_finance_year'          => 'sanitize_text_field',
                ),
                'alkautsar-donation-settings' => array(
                        'alkautsar_bank_name'    => 'sanitize_text_field',
                        'alkautsar_bank_account' => 'sanitize_text_field',
                        'alkautsar_bank_holder'  => 'sanitize_text_field',
                        'alkautsar_qris_image'   => 'esc_url_raw',
                        'alkautsar_whatsapp'     => 'sanitize_text_field',
                ),
                'alkautsar-contact-settings' => array(
                        'alkautsar_address'    => 'sanitize_textarea_field',
                        'alkautsar_phone'      => 'sanitize_text_field',
                        'alkautsar_email'      => 'sanitize_email',
                        'alkautsar_instagram'  => 'esc_url_raw',
                        'alkautsar_youtube'    => 'esc_url_raw',
                        'alkautsar_facebook'   => 'esc_url_raw',
                        'alkautsar_tiktok'     => 'esc_url_raw',
                        'alkautsar_telegram'   => 'esc_url_raw',
                        'alkautsar_map_lat'    => 'sanitize_text_field',
                        'alkautsar_map_lng'    => 'sanitize_text_field',
                ),
                'alkautsar-hero-settings' => array(
                        'alkautsar_hero_arabic'   => 'sanitize_text_field',
                        'alkautsar_hero_title'    => 'sanitize_text_field',
                        'alkautsar_hero_subtitle' => 'sanitize_text_field',
                        'alkautsar_hero_image'    => 'esc_url_raw',
                ),
                'alkautsar-about-settings' => array(
                        'alkautsar_about_eyebrow'      => 'sanitize_text_field',
                        'alkautsar_about_title'        => 'sanitize_text_field',
                        'alkautsar_about_lead'         => 'sanitize_textarea_field',
                        'alkautsar_about_text'         => 'sanitize_textarea_field',
                        'alkautsar_about_list'         => 'sanitize_textarea_field',
                        'alkautsar_about_badge_number' => 'sanitize_text_field',
                        'alkautsar_about_badge_label'  => 'sanitize_text_field',
                ),
                'alkautsar-transparency-settings' => array(
                        'alkautsar_transparency_eyebrow'      => 'sanitize_text_field',
                        'alkautsar_transparency_title'        => 'sanitize_text_field',
                        'alkautsar_transparency_text'         => 'sanitize_textarea_field',
                        'alkautsar_transparency_stat1_value'  => 'sanitize_text_field',
                        'alkautsar_transparency_stat1_label'  => 'sanitize_text_field',
                        'alkautsar_transparency_stat2_value'  => 'sanitize_text_field',
                        'alkautsar_transparency_stat2_label'  => 'sanitize_text_field',
                        'alkautsar_transparency_stat3_value'  => 'sanitize_text_field',
                        'alkautsar_transparency_stat3_label'  => 'sanitize_text_field',
                ),
        );
}

/**
 * Helper: render settings form header.
 */
function alkautsar_settings_header( $page_slug, $title, $description = '' ) {
        echo '<div class="wrap alkautsar-settings-page">';
        echo '<h1>' . esc_html( $title ) . '</h1>';
        if ( $description ) {
                echo '<p class="alkautsar-settings-desc">' . esc_html( $description ) . '</p>';
        }
        if ( isset( $_GET['settings-updated'] ) && '1' === $_GET['settings-updated'] ) { // phpcs:ignore
                echo '<div class="notice notice-success is-dismissible"><p><strong>Tersimpan!</strong> Perubahan berhasil disimpan.</p></div>';
        }
        echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
        wp_nonce_field( 'alkautsar_save_settings', 'alkautsar_settings_nonce' );
        echo '<input type="hidden" name="action" value="alkautsar_save_settings">';
        echo '<input type="hidden" name="alkautsar_settings_page" value="' . esc_attr( $page_slug ) . '">';
}

function alkautsar_settings_footer() {
        echo '<p style="margin-top:20px;">';
        submit_button( 'Simpan Perubahan', 'primary large', 'submit', false );
        echo '</p>';
        echo '</form></div>';
}

/**
 * Helper: render text field.
 */
function alkautsar_text_field( $setting_id, $label, $type = 'text', $description = '', $placeholder = '' ) {
        $value = get_theme_mod( $setting_id, '' );
        ?>
        <tr>
                <th scope="row"><label for="<?php echo esc_attr( $setting_id ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label></th>
                <td>
                        <input type="<?php echo esc_attr( $type ); ?>" id="<?php echo esc_attr( $setting_id ); ?>" name="<?php echo esc_attr( $setting_id ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="regular-text">
                        <?php if ( $description ) : ?>
                                <p class="description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                </td>
        </tr>
        <?php
}

function alkautsar_textarea_field( $setting_id, $label, $rows = 4, $description = '' ) {
        $value = get_theme_mod( $setting_id, '' );
        ?>
        <tr>
                <th scope="row" style="vertical-align:top;"><label for="<?php echo esc_attr( $setting_id ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label></th>
                <td>
                        <textarea id="<?php echo esc_attr( $setting_id ); ?>" name="<?php echo esc_attr( $setting_id ); ?>" rows="<?php echo esc_attr( $rows ); ?>" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
                        <?php if ( $description ) : ?>
                                <p class="description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                </td>
        </tr>
        <?php
}

function alkautsar_image_field( $setting_id, $label, $description = '' ) {
        $value = get_theme_mod( $setting_id, '' );
        ?>
        <tr>
                <th scope="row"><strong><?php echo esc_html( $label ); ?></strong></th>
                <td>
                        <input type="hidden" id="<?php echo esc_attr( $setting_id ); ?>" name="<?php echo esc_attr( $setting_id ); ?>" value="<?php echo esc_attr( $value ); ?>" class="alkautsar-image-url">
                        <div class="alkautsar-image-row">
                                <div class="alkautsar-image-preview" data-target="<?php echo esc_attr( $setting_id ); ?>">
                                        <?php if ( $value ) : ?>
                                                <img src="<?php echo esc_url( $value ); ?>" alt="">
                                        <?php else : ?>
                                                <span class="alkautsar-image-empty">Belum ada gambar</span>
                                        <?php endif; ?>
                                </div>
                                <div class="alkautsar-image-actions">
                                        <button type="button" class="button alkautsar-upload-btn" data-target="<?php echo esc_attr( $setting_id ); ?>">
                                                <span class="dashicons dashicons-upload"></span>
                                                Pilih / Upload Gambar
                                        </button>
                                        <button type="button" class="button alkautsar-clear-btn" data-target="<?php echo esc_attr( $setting_id ); ?>">
                                                Hapus
                                        </button>
                                        <?php if ( $description ) : ?>
                                                <p class="description"><?php echo esc_html( $description ); ?></p>
                                        <?php endif; ?>
                                </div>
                        </div>
                </td>
        </tr>
        <?php
}

/**
 * Page: Profil & DKM.
 */
function alkautsar_profile_settings_page() {
        alkautsar_settings_header( 'alkautsar-profile-settings', 'Profil & DKM Masjid', 'Atur sejarah masjid, visi-misi, dan data pengurus DKM. Perubahan otomatis tampil di halaman Profil website.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_textarea_field( 'alkautsar_profile_history', 'Sejarah Masjid', 5, 'Ceritakan sejarah singkat berdirinya masjid.' );
                alkautsar_textarea_field( 'alkautsar_vision', 'Visi', 3, 'Visi masjid — satu kalimat.' );
                alkautsar_textarea_field( 'alkautsar_mission', 'Misi (satu poin per baris)', 6, 'Tulis satu poin misi per baris. Contoh: "1. Menyelenggarakan ibadah dengan khusyuk."' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Struktur Pengurus DKM</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_dkm_chairman', 'Ketua DKM' );
                alkautsar_text_field( 'alkautsar_dkm_secretary', 'Sekretaris DKM' );
                alkautsar_text_field( 'alkautsar_dkm_treasurer', 'Bendahara DKM' );
                alkautsar_text_field( 'alkautsar_dkm_imam', 'Imam Masjid' );
                alkautsar_textarea_field( 'alkautsar_dkm_extra', 'Pengurus Bidang Lain (opsional)', 4, 'Format bebas. Contoh: "Bidang Dakwah — Ust. X | Bidang Sosial — Bapak Y"' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Finance Settings.
 */
function alkautsar_finance_settings_page() {
        $total_income  = get_theme_mod( 'alkautsar_finance_total_income', '0' );
        $total_expense = get_theme_mod( 'alkautsar_finance_total_expense', '0' );
        $balance       = (float) preg_replace( '/[^0-9]/', '', $total_income ) - (float) preg_replace( '/[^0-9]/', '', $total_expense );

        alkautsar_settings_header( 'alkautsar-finance-settings', 'Keuangan Masjid', 'Atur total pemasukan dan pengeluaran tahun berjalan. Untuk laporan detail per periode, gunakan menu "Laporan Keuangan" di sidebar.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_finance_year', 'Tahun Laporan', 'number', 'Contoh: 2026' );
                alkautsar_text_field( 'alkautsar_finance_total_income', 'Total Pemasukan Tahun Ini (Rp, angka saja)', 'text', 'Contoh: 248000000 untuk Rp 248.000.000', '248000000' );
                alkautsar_text_field( 'alkautsar_finance_total_expense', 'Total Pengeluaran Tahun Ini (Rp, angka saja)', 'text', 'Contoh: 215000000 untuk Rp 215.000.000', '215000000' );
                ?>
                <tr>
                        <th scope="row"><strong>Saldo (otomatis)</strong></th>
                        <td>
                                <p class="alkautsar-saldo-display" style="color:<?php echo $balance >= 0 ? '#128C7E' : '#B85C00'; ?>;">
                                        <?php echo esc_html( alkautsar_format_rupiah( $balance ) ); ?>
                                </p>
                                <p class="description">Saldo = Pemasukan - Pengeluaran. Otomatis dihitung.</p>
                        </td>
                </tr>
        </table>

        <div class="alkautsar-tip-box">
                <h4>Tips</h4>
                <p>
                        • Untuk laporan detail per periode (bulanan/mingguan/harian), gunakan menu <strong>"Laporan Keuangan"</strong> di sidebar.<br>
                        • Anda bisa upload PDF laporan keuangan di setiap post laporan.<br>
                        • Data di sini hanya untuk ringkasan tahun berjalan yang tampil di halaman Transparansi.
                </p>
        </div>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Donation Settings.
 */
function alkautsar_donation_settings_page() {
        alkautsar_settings_header( 'alkautsar-donation-settings', 'Pengaturan Donasi', 'Atur rekening bank, QRIS, dan nomor WhatsApp untuk konfirmasi donasi.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_bank_name', 'Nama Bank', 'text', '', 'Bank Syariah Indonesia (BSI)' );
                alkautsar_text_field( 'alkautsar_bank_account', 'Nomor Rekening', 'text', 'Angka saja tanpa spasi', '1234567890' );
                alkautsar_text_field( 'alkautsar_bank_holder', 'Atas Nama', 'text', '', 'Yayasan Masjid Al-Kautsar' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">QRIS</h3></td></tr>';
                alkautsar_image_field( 'alkautsar_qris_image', 'Gambar QRIS', 'Upload gambar QRIS (PNG/JPG). Pengunjung akan scan untuk donasi via e-wallet.' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Konfirmasi Donasi</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_whatsapp', 'Nomor WhatsApp (format internasional)', 'text', 'Contoh: 6281234567890 (tanpa + dan tanpa spasi)', '6281234567890' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Contact Settings.
 */
function alkautsar_contact_settings_page() {
        alkautsar_settings_header( 'alkautsar-contact-settings', 'Pengaturan Kontak', 'Atur alamat, telepon, email, sosial media, dan koordinat lokasi masjid (untuk peta).' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_textarea_field( 'alkautsar_address', 'Alamat Lengkap', 3 );
                alkautsar_text_field( 'alkautsar_phone', 'Nomor Telepon', 'text', '', '(021) 1234 5678' );
                alkautsar_text_field( 'alkautsar_email', 'Email', 'email' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Sosial Media</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_instagram', 'Instagram URL', 'url' );
                alkautsar_text_field( 'alkautsar_youtube', 'YouTube URL', 'url' );
                alkautsar_text_field( 'alkautsar_facebook', 'Facebook URL', 'url' );
                alkautsar_text_field( 'alkautsar_tiktok', 'TikTok URL', 'url' );
                alkautsar_text_field( 'alkautsar_telegram', 'Telegram URL', 'url' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Koordinat Lokasi (untuk Peta)</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_map_lat', 'Latitude', 'text', 'Contoh: -6.2088 (Jakarta). Cari koordinat masjid Anda di openstreetmap.org.', '-6.2088' );
                alkautsar_text_field( 'alkautsar_map_lng', 'Longitude', 'text', 'Contoh: 106.8456 (Jakarta).', '106.8456' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Hero Settings.
 */
function alkautsar_hero_settings_page() {
        alkautsar_settings_header( 'alkautsar-hero-settings', 'Beranda (Hero Section)', 'Atur tampilan bagian atas (hero) halaman beranda — ayat Arab, judul, subtitle, dan gambar masjid.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_hero_arabic', 'Ayat Arab (di atas judul)', 'text', 'Contoh: Bismillahirrahmanirrahim' );
                alkautsar_text_field( 'alkautsar_hero_title', 'Judul Utama', 'text', '', 'Selamat Datang di Masjid Al-Kautsar' );
                alkautsar_textarea_field( 'alkautsar_hero_subtitle', 'Subtitle', 2, 'Kalimat singkat di bawah judul.' );
                alkautsar_image_field( 'alkautsar_hero_image', 'Gambar Masjid', 'Upload foto masjid. Akan tampil di section "Tentang Kami" beranda dan halaman Profil.' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: About Section Settings (Beranda - Tentang Kami).
 */
function alkautsar_about_settings_page() {
        alkautsar_settings_header( 'alkautsar-about-settings', 'Beranda (Tentang Kami)', 'Atur tampilan section "Tentang Kami" di beranda — judul, paragraf, daftar keunggulan, dan badge.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_about_eyebrow', 'Label Section', 'text', 'Teks kecil di atas judul. Contoh: "Tentang Kami"', 'Tentang Kami' );
                alkautsar_text_field( 'alkautsar_about_title', 'Judul Section', 'text', '', 'Masjid Al-Kautsar — Baitullah yang Memuliakan Umat' );
                alkautsar_textarea_field( 'alkautsar_about_lead', 'Paragraf Utama (Lead)', 3, 'Paragraf pembuka yang menjelaskan masjid secara umum.' );
                alkautsar_textarea_field( 'alkautsar_about_text', 'Paragraf Kedua', 4, 'Paragraf pendukung yang menjelaskan program/aktivitas masjid.' );
                alkautsar_textarea_field( 'alkautsar_about_list', 'Daftar Keunggulan (satu per baris)', 5, 'Tulis satu keunggulan per baris. Maksimal 4-6 baris.' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Badge (Pojok Kanan Bawah Gambar)</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_about_badge_number', 'Angka Badge', 'text', 'Contoh: "12+" atau "5"', '12+' );
                alkautsar_text_field( 'alkautsar_about_badge_label', 'Label Badge', 'text', 'Contoh: "Tahun Mengabdi" atau "Program"', 'Tahun Mengabdi' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Transparency Section Settings (Beranda - Transparansi).
 */
function alkautsar_transparency_settings_page() {
        alkautsar_settings_header( 'alkautsar-transparency-settings', 'Beranda (Transparansi)', 'Atur tampilan section "Transparansi Donasi" di beranda — judul, paragraf, dan 3 statistik.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_transparency_eyebrow', 'Label Section', 'text', 'Teks kecil di atas judul.', 'Transparansi Donasi' );
                alkautsar_text_field( 'alkautsar_transparency_title', 'Judul Section', 'text', '', 'Amanah Anda, Kami Jaga dengan Terbuka' );
                alkautsar_textarea_field( 'alkautsar_transparency_text', 'Paragraf Penjelasan', 4, 'Paragraf yang menjelaskan komitmen transparansi masjid.' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Statistik 1</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_transparency_stat1_value', 'Nilai Statistik 1', 'text', 'Contoh: "Rp 248jt" atau "500"', 'Rp 248jt' );
                alkautsar_text_field( 'alkautsar_transparency_stat1_label', 'Label Statistik 1', 'text', 'Contoh: "Donasi Tahun Ini"', 'Donasi Tahun Ini' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Statistik 2</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_transparency_stat2_value', 'Nilai Statistik 2', 'text', '', '1.284' );
                alkautsar_text_field( 'alkautsar_transparency_stat2_label', 'Label Statistik 2', 'text', '', 'Donatur' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Statistik 3</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_transparency_stat3_value', 'Nilai Statistik 3', 'text', '', '37' );
                alkautsar_text_field( 'alkautsar_transparency_stat3_label', 'Label Statistik 3', 'text', '', 'Program Terealisasi' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Enqueue admin scripts only on settings pages.
 */
function alkautsar_settings_admin_scripts( $hook ) {
        // Only on our settings pages.
        $our_pages = array(
                'toplevel_page_alkautsar-profile-settings',
                'toplevel_page_alkautsar-finance-settings',
                'toplevel_page_alkautsar-donation-settings',
                'toplevel_page_alkautsar-contact-settings',
                'toplevel_page_alkautsar-hero-settings',
                'toplevel_page_alkautsar-about-settings',
                'toplevel_page_alkautsar-transparency-settings',
        );
        if ( ! in_array( $hook, $our_pages, true ) ) {
                return;
        }
        wp_enqueue_media();
        wp_enqueue_script(
                'alkautsar-settings-admin',
                ALKAUTSAR_URI . '/assets/js/admin-settings.js',
                array( 'jquery' ),
                ALKAUTSAR_VERSION,
                true
        );
        wp_enqueue_style(
                'alkautsar-settings-admin-css',
                ALKAUTSAR_URI . '/assets/css/admin-settings.css',
                array(),
                ALKAUTSAR_VERSION
        );
}
add_action( 'admin_enqueue_scripts', 'alkautsar_settings_admin_scripts' );
