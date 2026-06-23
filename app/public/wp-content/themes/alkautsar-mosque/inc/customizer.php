<?php
/**
 * Theme Customizer — minimal version.
 *
 * Customizer sekarang HANYA berisi pengaturan bawaan WordPress:
 *   - Site Identity (logo, judul, deskripsi)
 *   - Colors, Header Image, Background Image
 *   - Additional CSS
 *   - Menus, Widgets
 *
 * Semua pengaturan masjid (profil, donasi, kontak, keuangan, hero, tentang, transparansi,
 * koordinat lokasi, jadwal sholat) sudah dipindah ke menu admin terpisah di sidebar.
 * Lihat inc/admin-settings.php untuk detail.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer — kosong, semua setting sudah dipindah ke admin menu.
 * Function ini tetap ada untuk backward compatibility.
 */
function alkautsar_customize_register( $wp_customize ) {
	// Tambah section info yang menjelaskan pengaturan sudah dipindah.
	$wp_customize->add_section( 'alkautsar_info', array(
		'title'    => __( 'Pengaturan Masjid', 'alkautsar' ),
		'priority' => 30,
	) );

	$wp_customize->add_setting( 'alkautsar_info_notice', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'alkautsar_info_notice', array(
		'label'       => __( 'Informasi', 'alkautsar' ),
		'section'     => 'alkautsar_info',
		'type'        => 'hidden',
		'description' => __( 'Semua pengaturan masjid sudah dipindah ke menu admin terpisah di sidebar. Klik menu berikut untuk mengedit:', 'alkautsar' )
			. '<br><br>'
			. '• <strong>Beranda (Hero)</strong> — ayat Arab, judul, subtitle<br>'
			. '• <strong>Beranda (Tentang)</strong> — gambar masjid, judul section, paragraf, badge<br>'
			. '• <strong>Beranda (Transparansi)</strong> — judul, paragraf, statistik<br>'
			. '• <strong>Profil & DKM</strong> — sejarah, visi-misi<br>'
			. '• <strong>Pengurus DKM</strong> — tambah/edit anggota DKM dengan foto<br>'
			. '• <strong>Keuangan Masjid</strong> — total pemasukan/pengeluaran tahun ini<br>'
			. '• <strong>Laporan Keuangan</strong> — tambah laporan per periode<br>'
			. '• <strong>Pengaturan Donasi</strong> — bank, QRIS, WhatsApp<br>'
			. '• <strong>Pengaturan Kontak</strong> — alamat, telepon, email, sosial media, koordinat peta, metode sholat<br>'
			. '• <strong>Penerima Manfaat</strong> — data yatim, dhuafa, janda<br>'
			. '• <strong>Kegiatan</strong> — agenda mendatang<br>'
			. '• <strong>Program</strong> — program rutin masjid<br>'
			. '• <strong>Panduan Visual</strong> — tutorial lengkap',
	) );
}
add_action( 'customize_register', 'alkautsar_customize_register' );
