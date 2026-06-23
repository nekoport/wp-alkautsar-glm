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
 *
 * Urutan menu (logis — konten dulu, lalu pengaturan):
 * 4-7  : Konten masjid (Beranda sections, Profil)
 * 8-10 : Pengaturan (Donasi, Kontak, Keuangan)
 */
function alkautsar_settings_admin_menu() {
        // Beranda sections (group together).
        add_menu_page(
                __( 'Beranda (Hero)', 'alkautsar' ),
                __( 'Beranda (Hero)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-hero-settings',
                'alkautsar_hero_settings_page',
                'dashicons-format-image',
                4
        );

        add_menu_page(
                __( 'Beranda (Tentang)', 'alkautsar' ),
                __( 'Beranda (Tentang)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-about-settings',
                'alkautsar_about_settings_page',
                'dashicons-info-outline',
                5
        );

        add_menu_page(
                __( 'Beranda (Transparansi)', 'alkautsar' ),
                __( 'Beranda (Transparansi)', 'alkautsar' ),
                'edit_posts',
                'alkautsar-transparency-settings',
                'alkautsar_transparency_settings_page',
                'dashicons-visibility',
                6
        );

        // Profil masjid.
        add_menu_page(
                __( 'Profil Masjid', 'alkautsar' ),
                __( 'Profil Masjid', 'alkautsar' ),
                'edit_posts',
                'alkautsar-profile-settings',
                'alkautsar_profile_settings_page',
                'dashicons-building',
                7
        );

        // Pengaturan operasional.
        add_menu_page(
                __( 'Pengaturan Donasi', 'alkautsar' ),
                __( 'Pengaturan Donasi', 'alkautsar' ),
                'edit_posts',
                'alkautsar-donation-settings',
                'alkautsar_donation_settings_page',
                'dashicons-money-alt',
                8
        );

        add_menu_page(
                __( 'Pengaturan Kontak', 'alkautsar' ),
                __( 'Pengaturan Kontak', 'alkautsar' ),
                'edit_posts',
                'alkautsar-contact-settings',
                'alkautsar_contact_settings_page',
                'dashicons-phone',
                9
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
                ),
                'alkautsar-finance-settings' => array(
                        // Sudah dipindah ke Beranda (Transparansi). Page ini tidak lagi diregister.
                ),
                'alkautsar-donation-settings' => array(
                        'alkautsar_bank_name'    => 'sanitize_text_field',
                        'alkautsar_bank_account' => 'sanitize_text_field',
                        'alkautsar_bank_holder'  => 'sanitize_text_field',
                        'alkautsar_qris_image'   => 'esc_url_raw',
                        'alkautsar_whatsapp'     => 'sanitize_text_field',
                ),
                'alkautsar-contact-settings' => array(
                        'alkautsar_address'       => 'sanitize_textarea_field',
                        'alkautsar_phone'         => 'sanitize_text_field',
                        'alkautsar_email'         => 'sanitize_email',
                        'alkautsar_instagram'     => 'esc_url_raw',
                        'alkautsar_youtube'       => 'esc_url_raw',
                        'alkautsar_facebook'      => 'esc_url_raw',
                        'alkautsar_tiktok'        => 'esc_url_raw',
                        'alkautsar_telegram'      => 'esc_url_raw',
                        'alkautsar_map_lat'       => 'sanitize_text_field',
                        'alkautsar_map_lng'       => 'sanitize_text_field',
                        'alkautsar_prayer_method' => 'sanitize_text_field',
                ),
                'alkautsar-hero-settings' => array(
                        'alkautsar_hero_arabic'   => 'sanitize_text_field',
                        'alkautsar_hero_title'    => 'sanitize_text_field',
                        'alkautsar_hero_subtitle' => 'sanitize_text_field',
                ),
                'alkautsar-about-settings' => array(
                        'alkautsar_hero_image'           => 'esc_url_raw',
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
                        // Finance summary (merged from Keuangan Masjid).
                        'alkautsar_finance_year'              => 'sanitize_text_field',
                        'alkautsar_finance_total_income'      => 'sanitize_text_field',
                        'alkautsar_finance_total_expense'     => 'sanitize_text_field',
                        // Beneficiary counts (flexible, up to 5 categories).
                        'alkautsar_beneficiary_1_count'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_1_label'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_2_count'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_2_label'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_3_count'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_3_label'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_4_count'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_4_label'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_5_count'       => 'sanitize_text_field',
                        'alkautsar_beneficiary_5_label'       => 'sanitize_text_field',
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

function alkautsar_select_field( $setting_id, $label, $options, $description = '' ) {
        $value = get_theme_mod( $setting_id, '' );
        ?>
        <tr>
                <th scope="row"><label for="<?php echo esc_attr( $setting_id ); ?>"><strong><?php echo esc_html( $label ); ?></strong></label></th>
                <td>
                        <select id="<?php echo esc_attr( $setting_id ); ?>" name="<?php echo esc_attr( $setting_id ); ?>" class="regular-text">
                                <?php foreach ( $options as $val => $lbl ) : ?>
                                        <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $value, $val ); ?>><?php echo esc_html( $lbl ); ?></option>
                                <?php endforeach; ?>
                        </select>
                        <?php if ( $description ) : ?>
                                <p class="description"><?php echo esc_html( $description ); ?></p>
                        <?php endif; ?>
                </td>
        </tr>
        <?php
}

/**
 * Get list of prayer calculation methods (Aladhan API).
 */
function alkautsar_prayer_methods() {
        return array(
                '20' => __( 'Kemenag RI (Indonesia) — Recommended', 'alkautsar' ),
                '3'  => __( 'Muslim World League', 'alkautsar' ),
                '2'  => __( 'ISNA (North America)', 'alkautsar' ),
                '5'  => __( 'Egyptian General Authority', 'alkautsar' ),
                '4'  => __( 'Umm Al-Qura, Makkah', 'alkautsar' ),
                '1'  => __( 'University of Karachi', 'alkautsar' ),
        );
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
        alkautsar_settings_header( 'alkautsar-profile-settings', 'Profil Masjid', 'Atur sejarah masjid, visi, dan misi. Untuk data pengurus DKM (dengan foto), gunakan menu "Pengurus DKM" di sidebar.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_textarea_field( 'alkautsar_profile_history', 'Sejarah Masjid', 5, 'Ceritakan sejarah singkat berdirinya masjid.' );
                alkautsar_textarea_field( 'alkautsar_vision', 'Visi', 3, 'Visi masjid — satu kalimat.' );
                alkautsar_textarea_field( 'alkautsar_mission', 'Misi (satu poin per baris)', 6, 'Tulis satu poin misi per baris. Contoh: "1. Menyelenggarakan ibadah dengan khusyuk."' );
                ?>
        </table>

        <div class="alkautsar-tip-box">
                <h4>Tambah Pengurus DKM</h4>
                <p>Untuk menambah/edit anggota DKM (Ketua, Sekretaris, Bendahara, Imam, dll) lengkap dengan foto, gunakan menu <strong>"Pengurus DKM"</strong> di sidebar admin.</p>
                <p><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=dkm_member' ) ); ?>" class="button button-secondary">Kelola Pengurus DKM &rarr;</a></p>
        </div>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Finance Settings — dihapus, sudah digabung ke Beranda (Transparansi).
 */
function alkautsar_finance_settings_page() {
	// Empty stub — page tidak diregister lagi.
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
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Koordinat Lokasi (untuk Peta & Jadwal Sholat)</h3></td></tr>';
                alkautsar_text_field( 'alkautsar_map_lat', 'Latitude', 'text', 'Contoh: -6.2088 (Jakarta). Cari koordinat masjid Anda di openstreetmap.org.', '-6.2088' );
                alkautsar_text_field( 'alkautsar_map_lng', 'Longitude', 'text', 'Contoh: 106.8456 (Jakarta).', '106.8456' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Jadwal Sholat</h3></td></tr>';
                alkautsar_select_field( 'alkautsar_prayer_method', 'Metode Perhitungan Jadwal Sholat', alkautsar_prayer_methods(), 'Metode yang dipakai API Aladhan untuk menghitung jadwal sholat. Default: Kemenag RI.' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: Hero Settings.
 */
function alkautsar_hero_settings_page() {
        alkautsar_settings_header( 'alkautsar-hero-settings', 'Beranda (Hero Section)', 'Atur tampilan bagian atas (hero) halaman beranda — ayat Arab, judul, dan subtitle.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_text_field( 'alkautsar_hero_arabic', 'Ayat Arab (di atas judul)', 'text', 'Contoh: Bismillahirrahmanirrahim' );
                alkautsar_text_field( 'alkautsar_hero_title', 'Judul Utama', 'text', '', 'Selamat Datang di Masjid Al-Kautsar' );
                alkautsar_textarea_field( 'alkautsar_hero_subtitle', 'Subtitle', 2, 'Kalimat singkat di bawah judul.' );
                ?>
        </table>
        <?php
        alkautsar_settings_footer();
}

/**
 * Page: About Section Settings (Beranda - Tentang Kami).
 */
function alkautsar_about_settings_page() {
        alkautsar_settings_header( 'alkautsar-about-settings', 'Beranda (Tentang Kami)', 'Atur tampilan section "Tentang Kami" di beranda — gambar masjid, judul, paragraf, daftar keunggulan, dan badge.' );
        ?>
        <table class="form-table" role="presentation">
                <?php
                alkautsar_image_field( 'alkautsar_hero_image', 'Gambar Masjid', 'Upload foto masjid. Tampil di section "Tentang Kami" beranda dan halaman Profil.' );
                echo '<tr><td colspan="2"><h3 class="alkautsar-section-title">Konten Section</h3></td></tr>';
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
	$total_income  = get_theme_mod( 'alkautsar_finance_total_income', '0' );
	$total_expense = get_theme_mod( 'alkautsar_finance_total_expense', '0' );
	$balance       = (float) preg_replace( '/[^0-9]/', '', $total_income ) - (float) preg_replace( '/[^0-9]/', '', $total_expense );

	alkautsar_settings_header( 'alkautsar-transparency-settings', 'Beranda (Transparansi)', 'Atur section "Transparansi Donasi" di beranda: display text, ringkasan keuangan tahun ini, statistik, dan jumlah penerima manfaat. Semua dalam satu tempat.' );
	?>

	<h3 class="alkautsar-section-title" style="margin-top:0;">Display Section Beranda</h3>
	<table class="form-table" role="presentation">
		<?php
		alkautsar_text_field( 'alkautsar_transparency_eyebrow', 'Label Section', 'text', 'Teks kecil di atas judul.', 'Transparansi Donasi' );
		alkautsar_text_field( 'alkautsar_transparency_title', 'Judul Section', 'text', '', 'Amanah Anda, Kami Jaga dengan Terbuka' );
		alkautsar_textarea_field( 'alkautsar_transparency_text', 'Paragraf Penjelasan', 4, 'Paragraf yang menjelaskan komitmen transparansi masjid.' );
		?>
	</table>

	<h3 class="alkautsar-section-title">Ringkasan Keuangan Tahun Berjalan</h3>
	<p class="description" style="margin-bottom:16px;">Untuk laporan detail per periode (bulanan/mingguan/harian), gunakan menu <strong>"Laporan Keuangan"</strong> di sidebar. Data di sini hanya untuk ringkasan tahun berjalan di halaman Transparansi.</p>
	<table class="form-table" role="presentation">
		<?php
		alkautsar_text_field( 'alkautsar_finance_year', 'Tahun Laporan', 'number', 'Contoh: 2026' );
		alkautsar_text_field( 'alkautsar_finance_total_income', 'Total Pemasukan (Rp, angka saja)', 'text', 'Contoh: 248000000 untuk Rp 248.000.000', '248000000' );
		alkautsar_text_field( 'alkautsar_finance_total_expense', 'Total Pengeluaran (Rp, angka saja)', 'text', 'Contoh: 215000000 untuk Rp 215.000.000', '215000000' );
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

	<h3 class="alkautsar-section-title">Statistik Beranda (3 Kotak)</h3>
	<table class="form-table" role="presentation">
		<?php
		alkautsar_text_field( 'alkautsar_transparency_stat1_value', 'Statistik 1 — Nilai', 'text', 'Contoh: "Rp 248jt" atau "500"', 'Rp 248jt' );
		alkautsar_text_field( 'alkautsar_transparency_stat1_label', 'Statistik 1 — Label', 'text', '', 'Donasi Tahun Ini' );
		alkautsar_text_field( 'alkautsar_transparency_stat2_value', 'Statistik 2 — Nilai', 'text', '', '1.284' );
		alkautsar_text_field( 'alkautsar_transparency_stat2_label', 'Statistik 2 — Label', 'text', '', 'Donatur' );
		alkautsar_text_field( 'alkautsar_transparency_stat3_value', 'Statistik 3 — Nilai', 'text', '', '37' );
		alkautsar_text_field( 'alkautsar_transparency_stat3_label', 'Statistik 3 — Label', 'text', '', 'Program Terealisasi' );
		?>
	</table>

	<h3 class="alkautsar-section-title">Jumlah Penerima Manfaat</h3>
	<p class="description" style="margin-bottom:16px;">Isi jumlah penerima manfaat per kategori. Kosongkan jika tidak dipakai. Maksimal 5 kategori (bisa tambah lagi nanti dengan request ke developer). Total akan dihitung otomatis.</p>
	<table class="form-table" role="presentation">
		<?php for ( $i = 1; $i <= 5; $i++ ) :
			$count_default = ( 1 === $i ) ? '15' : ( ( 2 === $i ) ? '25' : ( ( 3 === $i ) ? '12' : '' ) );
			$label_default = ( 1 === $i ) ? __( 'Anak Yatim', 'alkautsar' ) : ( ( 2 === $i ) ? __( 'Fakir Miskin', 'alkautsar' ) : ( ( 3 === $i ) ? __( 'Janda', 'alkautsar' ) : '' ) );
			?>
			<tr>
				<th scope="row">Penerima Manfaat <?php echo esc_html( $i ); ?></th>
				<td style="display:flex; gap:8px; align-items:center;">
					<input type="number" min="0" name="alkautsar_beneficiary_<?php echo esc_attr( $i ); ?>_count" value="<?php echo esc_attr( get_theme_mod( "alkautsar_beneficiary_{$i}_count", $count_default ) ); ?>" placeholder="0" style="width:100px;">
					<input type="text" name="alkautsar_beneficiary_<?php echo esc_attr( $i ); ?>_label" value="<?php echo esc_attr( get_theme_mod( "alkautsar_beneficiary_{$i}_label", $label_default ) ); ?>" placeholder="<?php esc_attr_e( 'Nama kategori', 'alkautsar' ); ?>" style="flex:1; max-width:300px;">
				</td>
			</tr>
		<?php endfor; ?>
		<?php
		$total = 0;
		for ( $i = 1; $i <= 5; $i++ ) {
			$total += (int) preg_replace( '/[^0-9]/', '', get_theme_mod( "alkautsar_beneficiary_{$i}_count", '0' ) );
		}
		?>
		<tr>
			<th scope="row"><strong>Total (otomatis)</strong></th>
			<td>
				<p class="alkautsar-saldo-display" style="color:#B8901E;">
					<?php echo esc_html( number_format( $total ) ); ?>
					<span style="font-size:14px; color:#666; font-weight:normal; margin-left:8px;"><?php esc_html_e( 'Total Penerima Manfaat', 'alkautsar' ); ?></span>
				</p>
				<p class="description">Total dihitung otomatis dari 5 kategori di atas.</p>
			</td>
		</tr>
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
                'toplevel_page_alkautsar-hero-settings',
                'toplevel_page_alkautsar-about-settings',
                'toplevel_page_alkautsar-transparency-settings',
                'toplevel_page_alkautsar-profile-settings',
                'toplevel_page_alkautsar-donation-settings',
                'toplevel_page_alkautsar-contact-settings',
                'toplevel_page_alkautsar-finance-settings',
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
