<?php
/**
 * Theme Customizer — minimal version.
 *
 * Hanya berisi pengaturan teknis (Prayer Times coordinates & calculation method).
 * Pengaturan konten masjid (profil, donasi, kontak, keuangan, hero) sudah dipindah
 * ke menu admin terpisah (lihat inc/admin-settings.php) yang lebih ramah pengurus.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer — minimal, hanya prayer times settings.
 */
function alkautsar_customize_register( $wp_customize ) {

	// ─── Panel: Mosque Information ───────────────────────────────────────
	$wp_customize->add_panel( 'alkautsar_mosque', array(
		'title'    => __( 'Pengaturan Masjid', 'alkautsar' ),
		'priority' => 30,
	) );

	// ── Section: Prayer Times ──
	// Section ini dipertahankan di Customizer karena terkait konfigurasi teknis API
	// (koordinat & metode perhitungan). Untuk koordinat peta lokasi, gunakan menu
	// "Pengaturan Kontak" di sidebar admin.
	$wp_customize->add_section( 'alkautsar_prayer', array(
		'title' => __( 'Jadwal Sholat', 'alkautsar' ),
		'panel' => 'alkautsar_mosque',
	) );

	$wp_customize->add_setting( 'alkautsar_latitude', array(
		'default'           => '-6.2088',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'alkautsar_latitude', array(
		'label'       => __( 'Latitude (untuk perhitungan jadwal sholat)', 'alkautsar' ),
		'section'     => 'alkautsar_prayer',
		'type'        => 'text',
		'description' => __( 'Cari koordinat masjid Anda di openstreetmap.org atau google maps.', 'alkautsar' ),
	) );

	$wp_customize->add_setting( 'alkautsar_longitude', array(
		'default'           => '106.8456',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'alkautsar_longitude', array(
		'label'   => __( 'Longitude (untuk perhitungan jadwal sholat)', 'alkautsar' ),
		'section' => 'alkautsar_prayer',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'alkautsar_prayer_method', array(
		'default'           => '20',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'alkautsar_prayer_method', array(
		'label'   => __( 'Metode Perhitungan (Aladhan API)', 'alkautsar' ),
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

	// ── Section: Info — tampilkan notice di Customizer ──
	$wp_customize->add_section( 'alkautsar_info', array(
		'title' => __( 'Info Pengaturan', 'alkautsar' ),
		'panel' => 'alkautsar_mosque',
	) );

	$wp_customize->add_setting( 'alkautsar_info_notice', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alkautsar_info_notice', array(
		'label'       => __( 'Pengaturan Lainnya', 'alkautsar' ),
		'section'     => 'alkautsar_info',
		'type'        => 'hidden',
		'description' => __( '<strong>Pengaturan lain telah dipindah ke menu admin terpisah:</strong><br><br>• Profil & DKM — menu "Profil & DKM" di sidebar<br>• Donasi (Bank, QRIS, WhatsApp) — menu "Pengaturan Donasi"<br>• Kontak & Sosial Media — menu "Pengaturan Kontak"<br>• Keuangan Masjid — menu "Keuangan Masjid"<br>• Beranda (Hero) — menu "Beranda (Hero)"<br><br>Klik menu di sidebar untuk mengakses pengaturan tersebut.', 'alkautsar' ),
	) );
}
add_action( 'customize_register', 'alkautsar_customize_register' );

/**
 * Allow HTML in customizer description (for the info notice).
 */
function alkautsar_customizer_html_description( $wp_customize ) {
	// Description already supports HTML via wp_kses_post on output.
}
add_action( 'customize_controls_print_footer_scripts', 'alkautsar_customizer_html_description' );
