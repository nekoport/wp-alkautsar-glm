<?php
/**
 * Template Name: Donasi
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$bank_name    = get_theme_mod( 'alkautsar_bank_name', 'Bank Syariah Indonesia (BSI)' );
$bank_account = get_theme_mod( 'alkautsar_bank_account', '1234567890' );
$bank_holder  = get_theme_mod( 'alkautsar_bank_holder', 'Yayasan Masjid Al-Kautsar' );
$qris         = get_theme_mod( 'alkautsar_qris_image' );
$whatsapp     = get_theme_mod( 'alkautsar_whatsapp', '6281234567890' );
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Donasi & Sedekah', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Salurkan donasi Anda untuk kemakmuran rumah Allah.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">

		<section style="text-align:center; max-width:760px; margin: 0 auto 3rem;">
			<p class="section-eyebrow" style="padding-left:0; padding-bottom:1.5rem;"><?php esc_html_e( 'Tasharruf & Wakaf', 'alkautsar' ); ?></p>
			<p class="section-eyebrow::after" style="display:none;"></p>
			<h2 style="font-size: var(--fs-h2); margin-bottom:1rem;"><?php esc_html_e( 'Investasikan Harta Terbaikmu untuk Rumah Allah', 'alkautsar' ); ?></h2>
			<p style="font-size:1.0625rem; color: var(--ink-soft); line-height:1.7;">
				<?php esc_html_e( 'Setiap rupiah yang Anda salurkan menjadi sebab turunnya berkah, terjaganya rumah Allah, dan terpeliharanya ibadah jamaah. Pilih metode donasi yang paling mudah bagi Anda di bawah ini.', 'alkautsar' ); ?>
			</p>
		</section>

		<div class="donation-methods-page">
			<div class="donation-method-page donation-method-page--bank">
				<div class="donation-method-page__head">
					<span class="donation-method__badge"><?php esc_html_e( 'Transfer Bank', 'alkautsar' ); ?></span>
				</div>
				<p class="donation-method__bank" style="color:var(--ink-soft); margin-top:1rem;"><?php echo esc_html( $bank_name ); ?></p>
				<p class="donation-method__number" style="font-size:2.25rem; color:var(--accent-deep); margin:0.5rem 0;"><?php echo esc_html( $bank_account ); ?></p>
				<p class="donation-method__holder" style="color:var(--secondary); margin-bottom:1.5rem;"><?php echo esc_html( $bank_holder ); ?></p>
				<button class="btn btn--gold js-copy" data-copy="<?php echo esc_attr( $bank_account ); ?>" type="button">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
					<?php esc_html_e( 'Salin Nomor Rekening', 'alkautsar' ); ?>
				</button>
			</div>

			<div class="donation-method-page donation-method-page--qris">
				<div class="donation-method-page__head">
					<span class="donation-method__badge"><?php esc_html_e( 'QRIS', 'alkautsar' ); ?></span>
					<span class="donation-method__sub"><?php esc_html_e( 'Scan dari semua e-wallet & m-banking', 'alkautsar' ); ?></span>
				</div>
				<div class="donation-method-page__qr">
					<?php if ( $qris ) : ?>
						<img src="<?php echo esc_url( $qris ); ?>" alt="<?php esc_attr_e( 'QRIS Masjid Al-Kautsar', 'alkautsar' ); ?>" loading="lazy">
					<?php else : ?>
						<div class="donation-method__qr-placeholder" style="padding:2rem; text-align:center;">
							<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 0.75rem;"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><line x1="14" y1="14" x2="14" y2="21"/><line x1="18" y1="14" x2="18" y2="18"/><line x1="21" y1="14" x2="21" y2="21"/><line x1="14" y1="18" x2="18" y2="18"/></svg>
							<p><?php esc_html_e( 'QRIS belum diunggah. Silakan upload melalui Customizer.', 'alkautsar' ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<section class="donation-confirm-page">
			<h3><?php esc_html_e( 'Konfirmasi Donasi', 'alkautsar' ); ?></h3>
			<p><?php esc_html_e( 'Setelah transfer atau scan QRIS, mohon konfirmasi via WhatsApp agar donasi dapat kami catat dan akui dengan baik. Setiap donasi yang masuk akan dilaporkan secara transparan di halaman Transparansi.', 'alkautsar' ); ?></p>
			<a href="<?php echo esc_url( alkautsar_whatsapp_link( 'Assalamualaikum, saya ingin konfirmasi donasi untuk Masjid Al-Kautsar.' ) ); ?>" class="btn btn--whatsapp btn--lg" target="_blank" rel="noopener noreferrer">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
				<?php esc_html_e( 'Konfirmasi via WhatsApp', 'alkautsar' ); ?>
			</a>
		</section>

		<section style="margin-top:4rem;">
			<div class="section-head section-head--center">
				<p class="section-eyebrow"><?php esc_html_e( 'Penyaluran Donasi', 'alkautsar' ); ?></p>
				<h2 class="section-title"><?php esc_html_e( 'Ke Mana Donasi Anda Disalurkan?', 'alkautsar' ); ?></h2>
			</div>
			<div class="programs__grid" style="grid-template-columns: repeat(4, 1fr);">
				<div class="program-card" style="text-align:center; padding:1.5rem;">
					<div class="program-card__icon" style="margin:0 auto 1rem;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
					<h3 style="font-size:1rem; margin-bottom:0.5rem;"><?php esc_html_e( 'Santunan Dhuafa', 'alkautsar' ); ?></h3>
					<p style="font-size:0.8125rem; color:var(--ink-soft);"><?php esc_html_e( 'Yatim, janda, & fakir miskin.', 'alkautsar' ); ?></p>
				</div>
				<div class="program-card" style="text-align:center; padding:1.5rem;">
					<div class="program-card__icon" style="margin:0 auto 1rem;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
					<h3 style="font-size:1rem; margin-bottom:0.5rem;"><?php esc_html_e( 'Operasional Masjid', 'alkautsar' ); ?></h3>
					<p style="font-size:0.8125rem; color:var(--ink-soft);"><?php esc_html_e( 'Listrik, air, gaji pegawai.', 'alkautsar' ); ?></p>
				</div>
				<div class="program-card" style="text-align:center; padding:1.5rem;">
					<div class="program-card__icon" style="margin:0 auto 1rem;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/></svg></div>
					<h3 style="font-size:1rem; margin-bottom:0.5rem;"><?php esc_html_e( 'Pendidikan', 'alkautsar' ); ?></h3>
					<p style="font-size:0.8125rem; color:var(--ink-soft);"><?php esc_html_e( 'TPA, kajian, & pelatihan.', 'alkautsar' ); ?></p>
				</div>
				<div class="program-card" style="text-align:center; padding:1.5rem;">
					<div class="program-card__icon" style="margin:0 auto 1rem;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
					<h3 style="font-size:1rem; margin-bottom:0.5rem;"><?php esc_html_e( 'Acara Khusus', 'alkautsar' ); ?></h3>
					<p style="font-size:0.8125rem; color:var(--ink-soft);"><?php esc_html_e( 'Ramadhan, Qurban, & akbar.', 'alkautsar' ); ?></p>
				</div>
			</div>
		</section>

		<div style="text-align:center; margin-top:4rem; padding:2rem; background: var(--base-alt); border-radius: var(--radius-lg);">
			<h3 style="margin-bottom:1rem; font-size:1.5rem;"><?php esc_html_e( 'Mau lihat laporan penyaluran donasi?', 'alkautsar' ); ?></h3>
			<a href="<?php echo esc_url( home_url( '/transparansi' ) ); ?>" class="btn btn--primary"><?php esc_html_e( 'Lihat Halaman Transparansi', 'alkautsar' ); ?></a>
		</div>

	</div>
</main>

<?php get_footer(); ?>
