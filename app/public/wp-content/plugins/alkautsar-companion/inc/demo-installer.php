<?php
/**
 * Demo Content Installer — install contoh berita, kegiatan, program, beneficiary.
 *
 * @package AlKautsarCompanion
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Handle install demo form submission.
 * Dipanggil via admin-post.php?action=alkautsar_install_demo
 */
function alkautsar_companion_handle_install_demo() {
        // Capability check — bolehkan user ini install demo?
        if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'manage_options' ) ) {
                wp_die( esc_html__( 'Anda tidak punya izin untuk install demo content.', 'alkautsar-companion' ) );
        }

        // Verify nonce.
        check_admin_referer( 'alkautsar_install_demo' );

        // Install demo content.
        try {
                alkautsar_companion_create_demo_posts();
                alkautsar_companion_create_demo_events();
                alkautsar_companion_create_demo_programs();
                alkautsar_companion_create_demo_reports();
                alkautsar_companion_create_demo_beneficiaries();

                wp_safe_redirect( admin_url( 'admin.php?page=alkautsar-companion&installed=1' ) );
                exit;
        } catch ( Exception $e ) {
                wp_safe_redirect( admin_url( 'admin.php?page=alkautsar-companion&error=1' ) );
                exit;
        }
}
add_action( 'admin_post_alkautsar_install_demo', 'alkautsar_companion_handle_install_demo' );

function alkautsar_companion_create_demo_posts() {
        $demo_posts = array(
                array(
                        'title'   => 'Kajian Kitab Bulughul Maram Bersama Ust. Ahmad Fauzi',
                        'content' => "Alhamdulillah, Masjid Al-Kautsar kembali menyelenggarakan kajian rutin pekanan. Kali ini mengkaji Kitab Bulughul Maram bersama Ustadz Ahmad Fauzi, Lc.\n\nKajian dilaksanakan setiap Sabtu ba'da Maghrib di Aula Masjid Al-Kautsar. Para jamaah yang hadir sangat antusias mengikuti pembahasan tentang fiqih ibadah.\n\n<blockquote>Insya Allah, kajian ini akan berlangsung rutin setiap pekan. Mari hadirkan keluarga Anda untuk menimba ilmu bersama.</blockquote>\n\nUntuk informasi lebih lanjut, silakan hubungi sekretariat masjid via WhatsApp.",
                        'cat'     => 'Kajian',
                ),
                array(
                        'title'   => 'Buka Puasa Bersama 250 Jamaah Dhuafa',
                        'content' => "Alhamdulillah, Masjid Al-Kautsar sukses menyelenggarakan Buka Puasa Bersama yang dihadiri 250 jamaah dhuafa dari sekitar masjid.\n\nAcara dimulai dengan tausiyah singkat oleh Ustadz Muhammad Ilham, dilanjutkan dengan berbuka puasa bersama dan sholat Maghrib berjamaah.\n\nTerima kasih kepada para donatur yang telah menyalurkan infak dan sedekah. Semoga Allah membalas kebaikan Anda dengan berlipat ganda.",
                        'cat'     => 'Sosial',
                ),
                array(
                        'title'   => 'Pendaftaran TPA Semester Genap 2025/2026 Dibuka',
                        'content' => "Diberitahukan kepada seluruh jamaah, pendaftaran santri baru Taman Pendidikan Al-Qur'an (TPA) Masjid Al-Kautsar untuk semester genap tahun ajaran 2025/2026 telah dibuka.\n\n<strong>Biaya pendaftaran:</strong> Rp 50.000 (sekali)\n<strong>SPP bulanan:</strong> Rp 25.000\n\nPendaftaran dilakukan di sekretariat masjid setiap Sabtu &amp; Minggu, pukul 08.00 - 12.00 WIB. Persiapkan:\n\n<ul><li>Fotokopi akta kelahiran</li><li>Pass foto 2x3 (2 lembar)</li><li>Fotokopi KK</li></ul>\n\nMari persiapkan generasi qur'ani sejak dini!",
                        'cat'     => 'Pendidikan',
                ),
                array(
                        'title'   => 'Pengajian Akbar Memperingati Maulid Nabi Muhammad SAW',
                        'content' => "Dalam rangka memperingati Maulid Nabi Muhammad SAW, Masjid Al-Kautsar akan menyelenggarakan Pengajian Akbar insya Allah pada:\n\n<strong>Hari/Tanggal:</strong> Jumat, 7 Maret 2026\n<strong>Waktu:</strong> Ba'da Subuh - 11.00 WIB\n<strong>Lokasi:</strong> Ruang Utama Masjid Al-Kautsar\n<strong>Pembicara:</strong> KH. Abdullah Syafi'i\n\nMari hadir bersama keluarga untuk memperbanyak sholawat dan meneladani akhlak Rasulullah SAW.",
                        'cat'     => 'Ibadah',
                ),
                array(
                        'title'   => 'Santunan Bulanan untuk 25 Anak Yatim & Dhuafa',
                        'content' => "Alhamdulillah, program santunan bulanan Masjid Al-Kautsar kembali terselenggara. Sebanyak 25 anak yatim dan dhuafa menerima santunan berupa uang tunai dan paket sembako.\n\nProgram ini berjalan berkat dukungan donatur tetap yang konsisten menyalurkan infak setiap bulan. Bagi yang ingin bergabung sebagai donatur tetap, silakan konfirmasi via WhatsApp ke nomor masjid.\n\nSemoga Allah memberkahi harta dan keluarga para donatur.",
                        'cat'     => 'Sosial',
                ),
        );

        // Pastikan kategori ada.
        foreach ( array( 'Kajian', 'Sosial', 'Pendidikan', 'Ibadah' ) as $cat_name ) {
                if ( ! get_cat_ID( $cat_name ) ) {
                        wp_insert_category( array( 'cat_name' => $cat_name ) );
                }
        }

        foreach ( $demo_posts as $post_data ) {
                // Cek apakah sudah ada post dengan judul yang sama.
                $existing = get_page_by_title( $post_data['title'], OBJECT, 'post' );
                if ( $existing ) { continue; }

                $cat_id = get_cat_ID( $post_data['cat'] );
                $post_id = wp_insert_post( array(
                        'post_title'   => $post_data['title'],
                        'post_content' => $post_data['content'],
                        'post_status'  => 'publish',
                        'post_type'    => 'post',
                        'post_category'=> array( $cat_id ),
                        'post_date'    => gmdate( 'Y-m-d H:i:s', time() - rand( 1, 30 ) * DAY_IN_SECONDS ),
                ) );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                        wp_set_object_terms( $post_id, array( $cat_id ), 'category' );
                }
        }
}

function alkautsar_companion_create_demo_events() {
        $demo_events = array(
                array(
                        'title'    => 'Kajian Kitab Bulughul Maram',
                        'content'  => "Kajian rutin pekanan mengkaji Kitab Bulughul Maram bersama Ustadz Ahmad Fauzi, Lc. Pembahasan mencakup bab-bab fiqih ibadah (thaharah, sholat, zakat, puasa, haji).\n\nKajian terbuka untuk umum, gratis. Mari hadir bersama keluarga untuk menambah ilmu agama.",
                        'date'     => gmdate( 'Y-m-d', strtotime( '+7 days' ) ),
                        'time'     => '16:30',
                        'end_time' => '17:30',
                        'loc'      => 'Aula Masjid Al-Kautsar',
                        'spk'      => 'Ust. Ahmad Fauzi, Lc.',
                        'cat'      => 'kajian',
                ),
                array(
                        'title'    => 'Buka Puasa Bersama Dhuafa',
                        'content'  => "Acara buka puasa bersama 250 jamaah dhuafa dari sekitar masjid. Dimulai dengan tausiyah singkat, dilanjutkan berbuka bersama dan sholat Maghrib berjamaah.\n\nDonatur yang ingin menyumbang paket buka puasa (Rp 25.000/paket) silakan konfirmasi via WhatsApp.",
                        'date'     => gmdate( 'Y-m-d', strtotime( '+14 days' ) ),
                        'time'     => '17:00',
                        'end_time' => '19:00',
                        'loc'      => 'Halaman Masjid',
                        'spk'      => 'Ust. Muhammad Ilham',
                        'cat'      => 'sosial',
                ),
                array(
                        'title'    => 'Pendaftaran TPA Semester Genap',
                        'content'  => "Pendaftaran santri baru Taman Pendidikan Al-Qur'an untuk semester genap 2025/2026. Bawa fotokopi akta kelahiran, KK, dan 2 pass foto 2x3.\n\nBiaya pendaftaran Rp 50.000 (sekali), SPP bulanan Rp 25.000.",
                        'date'     => gmdate( 'Y-m-d', strtotime( '+21 days' ) ),
                        'time'     => '08:00',
                        'end_time' => '12:00',
                        'loc'      => 'Sekretariat TPA',
                        'spk'      => 'Ustadzah Siti Khadijah',
                        'cat'      => 'pendidikan',
                ),
                array(
                        'title'    => 'Maulid Nabi & Pengajian Akbar',
                        'content'  => "Pengajian akbar memperingati Maulid Nabi Muhammad SAW. Acara dimulai ba'da Subuh dengan pembacaan sholawat, dilanjutkan tausiyah oleh KH. Abdullah Syafi'i, dan ditutup dengan sholat Dzuhur berjamaah.\n\nKonsumsi disediakan panitia. Parkir terbatas, disarankan datang lebih awal.",
                        'date'     => gmdate( 'Y-m-d', strtotime( '+30 days' ) ),
                        'time'     => '05:30',
                        'end_time' => '11:00',
                        'loc'      => 'Ruang Utama Masjid',
                        'spk'      => "KH. Abdullah Syafi'i",
                        'cat'      => 'ibadah',
                ),
        );

        foreach ( $demo_events as $event ) {
                $existing = get_page_by_title( $event['title'], OBJECT, 'event' );
                if ( $existing ) { continue; }

                $post_id = wp_insert_post( array(
                        'post_title'   => $event['title'],
                        'post_content' => $event['content'],
                        'post_status'  => 'publish',
                        'post_type'    => 'event',
                ) );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                        update_post_meta( $post_id, 'alkautsar_event_date', $event['date'] );
                        update_post_meta( $post_id, 'alkautsar_event_time', $event['time'] );
                        update_post_meta( $post_id, 'alkautsar_event_end_time', $event['end_time'] );
                        update_post_meta( $post_id, 'alkautsar_event_location', $event['loc'] );
                        update_post_meta( $post_id, 'alkautsar_event_speaker', $event['spk'] );
                        update_post_meta( $post_id, 'alkautsar_event_category', $event['cat'] );
                }
        }
}

function alkautsar_companion_create_demo_programs() {
        $programs = array(
                array( 'Kajian Rutin Pekanan', "Pengajian tafsir, fiqih, dan akidah bersama para asatidz setiap ba'da Maghrib. Kajian gratis dan terbuka untuk umum.", 'book', "Setiap ba'da Maghrib", 'Aula Masjid' ),
                array( "Taman Pendidikan Al-Qur'an", 'Pendidikan mengaji dan akhlak untuk anak-anak usia 4-12 tahun setiap sore, Senin-Jumat.', 'graduation', 'Senin-Jumat 16:00', 'Ruang TPA' ),
                array( 'Santunan Dhuafa & Yatim', 'Program rutin santunan bulanan bagi dhuafa, anak yatim, dan kaum duafa di akhir setiap bulan.', 'heart', 'Akhir bulan', 'Aula Masjid' ),
                array( 'Jumat Berkah', 'Aksi sosial nasi kotak untuk pekerja dan musafir setiap hari Jumat sebelum sholat Jumat.', 'users', 'Jumat 11:30', 'Halaman Masjid' ),
                array( 'Pelatihan Kepemudaan', 'Pengembangan keterampilan dan kepemimpinan remaja masjid (RISMA) setiap Sabtu malam.', 'cube', 'Sabtu 19:00', 'Ruang RISMA' ),
                array( 'Konsultasi Syariah', 'Layanan tanya jawab dan konsultasi syariah bersama Dewan Syariah masjid (by appointment).', 'info', 'By appointment', 'Sekretariat' ),
        );

        foreach ( $programs as $p ) {
                list( $title, $desc, $icon, $sched, $loc ) = $p;
                $existing = get_page_by_title( $title, OBJECT, 'program' );
                if ( $existing ) { continue; }

                $post_id = wp_insert_post( array(
                        'post_title'   => $title,
                        'post_content' => $desc,
                        'post_excerpt' => $desc,
                        'post_status'  => 'publish',
                        'post_type'    => 'program',
                ) );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                        update_post_meta( $post_id, 'alkautsar_program_icon', $icon );
                        update_post_meta( $post_id, 'alkautsar_program_schedule', $sched );
                        update_post_meta( $post_id, 'alkautsar_program_location', $loc );
                }
        }
}

function alkautsar_companion_create_demo_reports() {
        $reports = array(
                array(
                        'title'  => 'Laporan Harian 1 Februari 2026',
                        'period' => 'daily',
                        'date'   => '2026-02-01',
                        'year'   => '2026',
                        'income' => '350000',
                        'expense'=> '200000',
                        'summary'=> 'Infak jamaah Subuh & Dzuhur. Pengeluaran untuk konsumsi pengurus.',
                        'items'  => "Pemasukan:\n- Infak jamaah Subuh: Rp 150.000\n- Infak jamaah Dzuhur: Rp 200.000\n\nPengeluaran:\n- Konsumsi pengurus: Rp 100.000\n- Perlengkapan kebersihan: Rp 100.000",
                ),
                array(
                        'title'  => 'Laporan Bulanan Januari 2026',
                        'period' => 'monthly',
                        'month'  => '01',
                        'year'   => '2026',
                        'income' => '21500000',
                        'expense'=> '18500000',
                        'summary'=> 'Pemasukan dari infak jamaah, donasi luar kota, dan kotak amal. Pengeluaran utama untuk operasional, gaji pegawai, dan santunan dhuafa.',
                        'items'  => "Pemasukan:\n- Infak jamaah Jumat (4x): Rp 12.000.000\n- Donasi luar kota: Rp 5.000.000\n- Kotak amal harian: Rp 4.500.000\n\nPengeluaran:\n- Listrik, air, internet: Rp 2.500.000\n- Gaji imam & marbot: Rp 6.000.000\n- Santunan dhuafa: Rp 5.000.000\n- Konsumsi kajian: Rp 2.000.000\n- Perawatan masjid: Rp 3.000.000",
                ),
                array(
                        'title'  => 'Laporan Bulanan Februari 2026',
                        'period' => 'monthly',
                        'month'  => '02',
                        'year'   => '2026',
                        'income' => '23500000',
                        'expense'=> '19500000',
                        'summary'=> 'Pemasukan meningkat karena banyak donatur menyambut Ramadhan. Pengeluaran tambahan untuk persiapan Ramadhan.',
                        'items'  => "Pemasukan:\n- Infak jamaah: Rp 14.000.000\n- Donasi khusus Ramadhan: Rp 7.000.000\n- Kotak amal: Rp 2.500.000\n\nPengeluaran:\n- Operasional: Rp 8.500.000\n- Santunan: Rp 5.500.000\n- Persiapan Ramadhan: Rp 5.500.000",
                ),
                array(
                        'title'  => 'Laporan Triwulan Q1 2026',
                        'period' => 'quarterly',
                        'year'   => '2026',
                        'income' => '62500000',
                        'expense'=> '52500000',
                        'summary'=> 'Total Q1 (Jan-Mar 2026). Saldo Rp 10.000.000 dialokasikan untuk kegiatan Ramadhan.',
                ),
        );

        foreach ( $reports as $r ) {
                $existing = get_page_by_title( $r['title'], OBJECT, 'financial_report' );
                if ( $existing ) { continue; }

                $post_id = wp_insert_post( array(
                        'post_title'   => $r['title'],
                        'post_content' => isset( $r['summary'] ) ? $r['summary'] : '',
                        'post_status'  => 'publish',
                        'post_type'    => 'financial_report',
                ) );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                        update_post_meta( $post_id, 'alkautsar_report_period', $r['period'] );
                        update_post_meta( $post_id, 'alkautsar_report_year', $r['year'] );
                        if ( isset( $r['month'] ) ) { update_post_meta( $post_id, 'alkautsar_report_month', $r['month'] ); }
                        if ( isset( $r['date'] ) ) { update_post_meta( $post_id, 'alkautsar_report_date', $r['date'] ); }
                        update_post_meta( $post_id, 'alkautsar_report_income', $r['income'] );
                        update_post_meta( $post_id, 'alkautsar_report_expense', $r['expense'] );
                        if ( isset( $r['summary'] ) ) { update_post_meta( $post_id, 'alkautsar_report_summary', $r['summary'] ); }
                        if ( isset( $r['items'] ) ) { update_post_meta( $post_id, 'alkautsar_report_items', $r['items'] ); }
                }
        }
}

function alkautsar_companion_create_demo_beneficiaries() {
        $beneficiaries = array(
                array( 'Anak Yatim A', 'yatim', '8', 'Santunan bulanan Rp 300.000', 'Yatim piatu, ayah wafat 2023' ),
                array( 'Anak Yatim B', 'yatim', '10', 'Santunan bulanan Rp 300.000', 'Yatim, ayah wafat 2024' ),
                array( 'Bapak H. Sopian', 'dhuafa', '65', 'Sembako bulanan Rp 200.000', 'Fakir miskin, tinggal sendiri' ),
                array( 'Ibu Hajjah Fatimah', 'janda', '58', 'Sembako bulanan Rp 200.000', 'Janda, suami wafat 2022' ),
        );

        foreach ( $beneficiaries as $b ) {
                list( $name, $cat, $age, $aid, $note ) = $b;
                $existing = get_page_by_title( $name, OBJECT, 'beneficiary' );
                if ( $existing ) { continue; }

                $post_id = wp_insert_post( array(
                        'post_title'   => $name,
                        'post_status'  => 'publish',
                        'post_type'    => 'beneficiary',
                ) );

                if ( $post_id && ! is_wp_error( $post_id ) ) {
                        update_post_meta( $post_id, 'alkautsar_beneficiary_category', $cat );
                        update_post_meta( $post_id, 'alkautsar_beneficiary_age', $age );
                        update_post_meta( $post_id, 'alkautsar_beneficiary_aid', $aid );
                        update_post_meta( $post_id, 'alkautsar_beneficiary_note', $note );
                }
        }
}
