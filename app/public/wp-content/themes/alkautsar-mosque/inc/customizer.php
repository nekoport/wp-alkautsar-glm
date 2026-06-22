<?php
/**
 * Theme Customizer — mosque-specific settings.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Register customizer panels, sections, settings.
 */
function alkautsar_customize_register( $wp_customize ) {

        // ─── Panel: Mosque Information ───────────────────────────────────────
        $wp_customize->add_panel( 'alkautsar_mosque', array(
                'title'    => __( 'Mosque Settings', 'alkautsar' ),
                'priority' => 30,
        ) );

        // ── Section: Prayer Times ──
        $wp_customize->add_section( 'alkautsar_prayer', array(
                'title' => __( 'Prayer Times', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_latitude', array(
                'default'           => '-6.2088',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'alkautsar_latitude', array(
                'label'   => __( 'Latitude', 'alkautsar' ),
                'section' => 'alkautsar_prayer',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_longitude', array(
                'default'           => '106.8456',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'alkautsar_longitude', array(
                'label'   => __( 'Longitude', 'alkautsar' ),
                'section' => 'alkautsar_prayer',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_prayer_method', array(
                'default'           => '20',
                'sanitize_callback' => 'sanitize_text_field',
                'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( 'alkautsar_prayer_method', array(
                'label'   => __( 'Calculation Method (Aladhan API)', 'alkautsar' ),
                'section' => 'alkautsar_prayer',
                'type'    => 'select',
                'choices' => array(
                        '3'  => __( 'Muslim World League', 'alkautsar' ),
                        '2'  => __( 'ISNA', 'alkautsar' ),
                        '5'  => __( 'Egyptian Authority', 'alkautsar' ),
                        '4'  => __( 'Umm Al-Qura, Makkah', 'alkautsar' ),
                        '20' => __( 'Kemenag RI (Indonesia)', 'alkautsar' ),
                ),
        ) );

        // ── Section: Donation ──
        $wp_customize->add_section( 'alkautsar_donation', array(
                'title' => __( 'Donation', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_bank_name', array(
                'default'           => 'Bank Syariah Indonesia (BSI)',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_bank_name', array(
                'label'   => __( 'Bank Name', 'alkautsar' ),
                'section' => 'alkautsar_donation',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_bank_account', array(
                'default'           => '1234567890',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_bank_account', array(
                'label'   => __( 'Account Number', 'alkautsar' ),
                'section' => 'alkautsar_donation',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_bank_holder', array(
                'default'           => 'Yayasan Masjid Al-Kautsar',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_bank_holder', array(
                'label'   => __( 'Account Holder', 'alkautsar' ),
                'section' => 'alkautsar_donation',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_qris_image', array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control(
                $wp_customize, 'alkautsar_qris_image', array(
                        'label'   => __( 'QRIS QR Code Image', 'alkautsar' ),
                        'section' => 'alkautsar_donation',
                )
        ) );

        $wp_customize->add_setting( 'alkautsar_whatsapp', array(
                'default'           => '6281234567890',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_whatsapp', array(
                'label'       => __( 'WhatsApp Number (intl. format, no +)', 'alkautsar' ),
                'section'     => 'alkautsar_donation',
                'type'        => 'text',
                'description' => __( 'e.g. 6281234567890', 'alkautsar' ),
        ) );

        // ── Section: Contact ──
        $wp_customize->add_section( 'alkautsar_contact', array(
                'title' => __( 'Contact', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_address', array(
                'default'           => 'Jl. Masjid Al-Kautsar No. 1, Jakarta',
                'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        $wp_customize->add_control( 'alkautsar_address', array(
                'label'   => __( 'Address', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( 'alkautsar_phone', array(
                'default'           => '(021) 1234 5678',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_phone', array(
                'label'   => __( 'Phone', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_email', array(
                'default'           => 'info@masjidal-kautsar.id',
                'sanitize_callback' => 'sanitize_email',
        ) );
        $wp_customize->add_control( 'alkautsar_email', array(
                'label'   => __( 'Email', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'email',
        ) );

        $wp_customize->add_setting( 'alkautsar_instagram', array(
                'default'           => 'https://instagram.com/',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'alkautsar_instagram', array(
                'label'   => __( 'Instagram URL', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'url',
        ) );

        $wp_customize->add_setting( 'alkautsar_youtube', array(
                'default'           => 'https://youtube.com/',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'alkautsar_youtube', array(
                'label'   => __( 'YouTube URL', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'url',
        ) );

        $wp_customize->add_setting( 'alkautsar_facebook', array(
                'default'           => 'https://facebook.com/',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'alkautsar_facebook', array(
                'label'   => __( 'Facebook URL', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'url',
        ) );

        $wp_customize->add_setting( 'alkautsar_tiktok', array(
                'default'           => 'https://tiktok.com/',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'alkautsar_tiktok', array(
                'label'   => __( 'TikTok URL', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'url',
        ) );

        $wp_customize->add_setting( 'alkautsar_telegram', array(
                'default'           => 'https://t.me/',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'alkautsar_telegram', array(
                'label'   => __( 'Telegram URL', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'url',
        ) );

        // Embed peta lokasi (OpenStreetMap — tidak butuh API key, gratis selamanya).
        $wp_customize->add_setting( 'alkautsar_map_lat', array(
                'default'           => '-6.2088',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_map_lat', array(
                'label'       => __( 'Latitude Lokasi Masjid (untuk Peta)', 'alkautsar' ),
                'section'     => 'alkautsar_contact',
                'type'        => 'text',
                'description' => __( 'Sama dengan latitude jadwal sholat. Dipakai untuk embed OpenStreetMap.', 'alkautsar' ),
        ) );

        $wp_customize->add_setting( 'alkautsar_map_lng', array(
                'default'           => '106.8456',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_map_lng', array(
                'label'   => __( 'Longitude Lokasi Masjid (untuk Peta)', 'alkautsar' ),
                'section' => 'alkautsar_contact',
                'type'    => 'text',
        ) );

        // ── Section: Profil Masjid & DKM ──
        $wp_customize->add_section( 'alkautsar_profile', array(
                'title' => __( 'Profil & DKM', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_profile_history', array(
                'default'           => 'Masjid Al-Kautsar didirikan pada tahun 2012 oleh para tokoh masyarakat setempat. Berawal dari sebuah musala kecil, kini berkembang menjadi masjid yang melayani ribuan jamaah setiap pekannya.',
                'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'alkautsar_profile_history', array(
                'label'   => __( 'Sejarah Masjid', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( 'alkautsar_vision', array(
                'default'           => 'Menjadi pusat peradaban Islam yang rahmatan lil \'alamin, menebar kebaikan dan ilmu bagi seluruh umat.',
                'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        $wp_customize->add_control( 'alkautsar_vision', array(
                'label'   => __( 'Visi', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( 'alkautsar_mission', array(
                'default'           => "1. Menyelenggarakan ibadah dengan khusyuk dan benar.\n2. Membina masyarakat melalui pendidikan Al-Qur'an dan kajian rutin.\n3. Memberdayakan ekonomi umat melalui program sosial.\n4. Membangun ukhuwah islamiyah antar jamaah.\n5. Menjadi pusat informasi & layanan keagamaan.",
                'sanitize_callback' => 'sanitize_textarea_field',
        ) );
        $wp_customize->add_control( 'alkautsar_mission', array(
                'label'   => __( 'Misi (satu poin per baris)', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( 'alkautsar_dkm_chairman', array(
                'default'           => 'H. Ahmad Rifai',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_dkm_chairman', array(
                'label'   => __( 'Ketua DKM', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_dkm_secretary', array(
                'default'           => 'Ust. Muhammad Ilham',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_dkm_secretary', array(
                'label'   => __( 'Sekretaris DKM', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_dkm_treasurer', array(
                'default'           => 'H. Sopian Hidayat',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_dkm_treasurer', array(
                'label'   => __( 'Bendahara DKM', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_dkm_imam', array(
                'default'           => 'Ust. Ahmad Fauzi, Lc.',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_dkm_imam', array(
                'label'   => __( 'Imam Masjid', 'alkautsar' ),
                'section' => 'alkautsar_profile',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_dkm_extra', array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'alkautsar_dkm_extra', array(
                'label'       => __( 'Pengurus Lain (opsional, format bebas)', 'alkautsar' ),
                'section'     => 'alkautsar_profile',
                'type'        => 'textarea',
                'description' => __( 'Contoh: Bidang Dakwah — Ust. X | Bidang Sosial — Bapak Y', 'alkautsar' ),
        ) );

        // ── Section: Laporan Keuangan ──
        $wp_customize->add_section( 'alkautsar_finance', array(
                'title' => __( 'Laporan Keuangan', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_finance_total_income', array(
                'default'           => '248000000',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_finance_total_income', array(
                'label'       => __( 'Total Pemasukan Tahun Ini (Rp, angka saja)', 'alkautsar' ),
                'section'     => 'alkautsar_finance',
                'type'        => 'text',
                'description' => __( 'Contoh: 248000000 untuk Rp 248.000.000', 'alkautsar' ),
        ) );

        $wp_customize->add_setting( 'alkautsar_finance_total_expense', array(
                'default'           => '215000000',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_finance_total_expense', array(
                'label'   => __( 'Total Pengeluaran Tahun Ini (Rp, angka saja)', 'alkautsar' ),
                'section' => 'alkautsar_finance',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_finance_year', array(
                'default'           => gmdate( 'Y' ),
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_finance_year', array(
                'label'   => __( 'Tahun Laporan', 'alkautsar' ),
                'section' => 'alkautsar_finance',
                'type'    => 'text',
        ) );

        // ── Section: Hero ──
        $wp_customize->add_section( 'alkautsar_hero', array(
                'title' => __( 'Hero Section', 'alkautsar' ),
                'panel' => 'alkautsar_mosque',
        ) );

        $wp_customize->add_setting( 'alkautsar_hero_arabic', array(
                'default'           => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_hero_arabic', array(
                'label'   => __( 'Arabic Verse', 'alkautsar' ),
                'section' => 'alkautsar_hero',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_hero_title', array(
                'default'           => 'Selamat Datang di Masjid Al-Kautsar',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_hero_title', array(
                'label'   => __( 'Hero Title', 'alkautsar' ),
                'section' => 'alkautsar_hero',
                'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'alkautsar_hero_subtitle', array(
                'default'           => 'Rumah ibadah, pusat dakwah, dan taman kebaikan bagi umat.',
                'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( 'alkautsar_hero_subtitle', array(
                'label'   => __( 'Hero Subtitle', 'alkautsar' ),
                'section' => 'alkautsar_hero',
                'type'    => 'textarea',
        ) );

        $wp_customize->add_setting( 'alkautsar_hero_image', array(
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
        ) );
        $wp_customize->add_control( new WP_Customize_Image_Control(
                $wp_customize, 'alkautsar_hero_image', array(
                        'label'   => __( 'Hero Background Image', 'alkautsar' ),
                        'section' => 'alkautsar_hero',
                )
        ) );
}
add_action( 'customize_register', 'alkautsar_customize_register' );
