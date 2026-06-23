<?php
/**
 * Template Name: Transparansi
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$total_income  = get_theme_mod( 'alkautsar_finance_total_income', '0' );
$total_expense = get_theme_mod( 'alkautsar_finance_total_expense', '0' );
$finance_year  = get_theme_mod( 'alkautsar_finance_year', gmdate( 'Y' ) );
$balance       = (float) preg_replace( '/[^0-9]/', '', $total_income ) - (float) preg_replace( '/[^0-9]/', '', $total_expense );

$beneficiary_counts = alkautsar_get_beneficiary_counts();
$total_beneficiaries = array_sum( $beneficiary_counts );
$reports = alkautsar_get_financial_reports( 12 );
?>

<header class="page-header">
        <div class="container">
                <h1><?php esc_html_e( 'Transparansi Donasi', 'alkautsar' ); ?></h1>
                <p class="breadcrumb"><?php esc_html_e( 'Amanah Anda, kami jaga dengan terbuka.', 'alkautsar' ); ?></p>
        </div>
</header>

<main id="primary" class="site-main">
        <div class="container page-content">

                <!-- Summary -->
                <section style="text-align:center; max-width:760px; margin:0 auto 3rem;">
                        <p class="section-eyebrow" style="padding-left:0; padding-bottom:1.5rem;"><?php echo esc_html( sprintf( __( 'Laporan Tahun %s', 'alkautsar' ), $finance_year ) ); ?></p>
                        <h2 style="font-size: var(--fs-h2); margin-bottom:1rem;"><?php esc_html_e( 'Ringkasan Keuangan Tahun Ini', 'alkautsar' ); ?></h2>
                        <p style="color: var(--ink-soft);"><?php esc_html_e( 'Setiap donasi yang masuk dicatat, dikelola, dan dilaporkan secara terbuka. Berikut ringkasan pemasukan, pengeluaran, dan saldo tahun berjalan.', 'alkautsar' ); ?></p>
                </section>

                <div class="finance-summary">
                        <div class="finance-summary__card finance-summary__card--income">
                                <p class="finance-summary__label"><?php esc_html_e( 'Total Pemasukan', 'alkautsar' ); ?></p>
                                <p class="finance-summary__value"><?php echo esc_html( alkautsar_format_rupiah( $total_income ) ); ?></p>
                                <p class="finance-summary__sub"><?php esc_html_e( 'Donasi, infak, & sedekah jamaah', 'alkautsar' ); ?></p>
                        </div>
                        <div class="finance-summary__card finance-summary__card--expense">
                                <p class="finance-summary__label"><?php esc_html_e( 'Total Pengeluaran', 'alkautsar' ); ?></p>
                                <p class="finance-summary__value"><?php echo esc_html( alkautsar_format_rupiah( $total_expense ) ); ?></p>
                                <p class="finance-summary__sub"><?php esc_html_e( 'Operasional, santunan, & program', 'alkautsar' ); ?></p>
                        </div>
                        <div class="finance-summary__card finance-summary__card--balance">
                                <p class="finance-summary__label"><?php esc_html_e( 'Saldo', 'alkautsar' ); ?></p>
                                <p class="finance-summary__value"><?php echo esc_html( alkautsar_format_rupiah( $balance ) ); ?></p>
                                <p class="finance-summary__sub"><?php esc_html_e( 'Dialokasikan untuk kebutuhan mendesak', 'alkautsar' ); ?></p>
                        </div>
                </div>

                <!-- Beneficiaries -->
                <section style="margin-top:4rem;">
                        <div class="section-head section-head--center">
                                <p class="section-eyebrow"><?php esc_html_e( 'Penerima Manfaat', 'alkautsar' ); ?></p>
                                <h2 class="section-title"><?php esc_html_e( 'Data Penerima Santunan', 'alkautsar' ); ?></h2>
                                <p class="section-desc"><?php esc_html_e( 'Untuk melindungi privasi, sebagian nama ditulis dalam inisial.', 'alkautsar' ); ?></p>
                        </div>

                        <div class="beneficiary-stats">
                                <?php
                                $cat_labels = alkautsar_beneficiary_categories();
                                foreach ( $beneficiary_counts as $cat => $count ) :
                                        ?>
                                        <div class="beneficiary-stat beneficiary-stat--<?php echo esc_attr( $cat ); ?>">
                                                <span class="beneficiary-stat__number"><?php echo esc_html( $count ); ?></span>
                                                <span class="beneficiary-stat__label"><?php echo esc_html( $cat_labels[ $cat ] ); ?></span>
                                        </div>
                                <?php endforeach; ?>
                        </div>

                        <?php
                        // Tampilkan daftar beneficiaries per kategori (hanya yang ada datanya).
                        foreach ( $beneficiary_counts as $cat => $count ) :
                                if ( $count === 0 ) { continue; }
                                $q = alkautsar_get_beneficiaries( $cat, 50 );
                                if ( ! $q->have_posts() ) { continue; }
                                ?>
                                <div class="beneficiary-list">
                                        <h3 class="beneficiary-list__title"><?php echo esc_html( $cat_labels[ $cat ] ); ?> (<?php echo esc_html( $count ); ?>)</h3>
                                        <div class="beneficiary-list__grid">
                                                <?php while ( $q->have_posts() ) : $q->the_post(); 
                                                        $age = get_post_meta( get_the_ID(), 'alkautsar_beneficiary_age', true );
                                                        $aid = get_post_meta( get_the_ID(), 'alkautsar_beneficiary_aid', true );
                                                        ?>
                                                        <div class="beneficiary-item">
                                                                <div class="beneficiary-item__avatar">
                                                                        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); } else { echo esc_html( strtoupper( substr( get_the_title(), 0, 1 ) ) ); } ?>
                                                                </div>
                                                                <div class="beneficiary-item__info">
                                                                        <p class="beneficiary-item__name"><?php the_title(); ?></p>
                                                                        <?php if ( $age ) : ?><span class="beneficiary-item__age"><?php echo esc_html( sprintf( __( '%s tahun', 'alkautsar' ), $age ) ); ?></span><?php endif; ?>
                                                                        <?php if ( $aid ) : ?><p class="beneficiary-item__aid"><?php echo esc_html( $aid ); ?></p><?php endif; ?>
                                                                </div>
                                                        </div>
                                                <?php endwhile; wp_reset_postdata(); ?>
                                        </div>
                                </div>
                        <?php endforeach; ?>

                        <?php if ( $total_beneficiaries === 0 ) : ?>
                                <p style="text-align:center; padding:3rem; color: var(--ink-soft); background: var(--base-alt); border-radius: var(--radius-lg);">
                                        <?php esc_html_e( 'Belum ada data penerima manfaat. Admin dapat menambahkannya dari dashboard → Penerima Manfaat.', 'alkautsar' ); ?>
                                </p>
                        <?php endif; ?>
                </section>

                <!-- Financial Reports -->
                <section style="margin-top:4rem;">
                        <div class="section-head section-head--center">
                                <p class="section-eyebrow"><?php esc_html_e( 'Laporan Keuangan', 'alkautsar' ); ?></p>
                                <h2 class="section-title"><?php esc_html_e( 'Dokumen Laporan Per Periode', 'alkautsar' ); ?></h2>
                                <p class="section-desc"><?php esc_html_e( 'Unduh laporan keuangan dalam format PDF untuk transparansi penuh.', 'alkautsar' ); ?></p>
                        </div>

                        <?php if ( $reports->have_posts() ) : ?>
                                <div class="reports-list">
                                        <?php while ( $reports->have_posts() ) : $reports->the_post();
                                                $period  = get_post_meta( get_the_ID(), 'alkautsar_report_period', true );
                                                $year    = get_post_meta( get_the_ID(), 'alkautsar_report_year', true );
                                                $month   = get_post_meta( get_the_ID(), 'alkautsar_report_month', true );
                                                $date    = get_post_meta( get_the_ID(), 'alkautsar_report_date', true );
                                                $pdf     = get_post_meta( get_the_ID(), 'alkautsar_report_pdf', true );
                                                $inc     = get_post_meta( get_the_ID(), 'alkautsar_report_income', true );
                                                $exp     = get_post_meta( get_the_ID(), 'alkautsar_report_expense', true );
                                                $summary = get_post_meta( get_the_ID(), 'alkautsar_report_summary', true );
                                                $items   = get_post_meta( get_the_ID(), 'alkautsar_report_items', true );
                                                $period_label = alkautsar_get_period_label( $period, $month, $year, $date );
                                                ?>
                                                <article class="report-item">
                                                        <div class="report-item__icon">
                                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
                                                        </div>
                                                        <div class="report-item__body">
                                                                <h3 class="report-item__title"><?php echo esc_html( $period_label ); ?></h3>
                                                                <p class="report-item__subtitle"><?php the_title(); ?></p>
                                                                <?php if ( $inc || $exp ) : ?>
                                                                        <div class="report-item__finance">
                                                                                <span class="report-item__income"><?php esc_html_e( 'Masuk:', 'alkautsar' ); ?> <strong><?php echo esc_html( alkautsar_format_rupiah( $inc ) ); ?></strong></span>
                                                                                <span class="report-item__expense"><?php esc_html_e( 'Keluar:', 'alkautsar' ); ?> <strong><?php echo esc_html( alkautsar_format_rupiah( $exp ) ); ?></strong></span>
                                                                        </div>
                                                                <?php endif; ?>
                                                                <?php if ( $summary ) : ?>
                                                                        <p class="report-item__summary"><?php echo esc_html( $summary ); ?></p>
                                                                <?php endif; ?>
                                                                <?php if ( $items ) : ?>
                                                                        <details class="report-item__details">
                                                                                <summary><?php esc_html_e( 'Lihat rincian item', 'alkautsar' ); ?></summary>
                                                                                <pre style="white-space:pre-wrap; font-family:inherit; font-size:0.875rem; background:var(--base-alt); padding:0.75rem 1rem; border-radius:var(--radius-sm); margin-top:0.5rem; color:var(--ink-soft);"><?php echo esc_html( $items ); ?></pre>
                                                                        </details>
                                                                <?php endif; ?>
                                                        </div>
                                                        <div class="report-item__action">
                                                                <?php if ( $pdf ) : ?>
                                                                        <a href="<?php echo esc_url( $pdf ); ?>" class="btn btn--primary btn--sm" target="_blank" rel="noopener noreferrer">
                                                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                                                <?php esc_html_e( 'Unduh PDF', 'alkautsar' ); ?>
                                                                        </a>
                                                                <?php else : ?>
                                                                        <span style="font-size:0.75rem; color: var(--ink-soft); font-style:italic;"><?php esc_html_e( 'PDF belum diunggah', 'alkautsar' ); ?></span>
                                                                <?php endif; ?>
                                                        </div>
                                                </article>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                        <?php else : ?>
                                <p style="text-align:center; padding:3rem; color: var(--ink-soft); background: var(--base-alt); border-radius: var(--radius-lg);">
                                        <?php esc_html_e( 'Belum ada laporan keuangan. Admin dapat menambahkannya dari dashboard → Laporan Keuangan.', 'alkautsar' ); ?>
                                </p>
                        <?php endif; ?>
                </section>

                <!-- CTA Donation -->
                <div style="text-align:center; margin-top:4rem; padding:2rem; background:linear-gradient(135deg, #2A140A 0%, #3B1E12 100%); color:white; border-radius: var(--radius-lg);">
                        <h3 style="color:white; margin-bottom:1rem; font-size:1.5rem;"><?php esc_html_e( 'Yakin dengan Amanah Kami?', 'alkautsar' ); ?></h3>
                        <p style="color: rgba(255,255,255,0.8); margin-bottom:1.5rem;"><?php esc_html_e( 'Salurkan donasi terbaik Anda untuk kemakmuran masjid.', 'alkautsar' ); ?></p>
                        <a href="<?php echo esc_url( home_url( '/donasi' ) ); ?>" class="btn btn--gold btn--lg"><?php esc_html_e( 'Donasi Sekarang', 'alkautsar' ); ?></a>
                </div>

        </div>
</main>

<?php get_footer(); ?>
