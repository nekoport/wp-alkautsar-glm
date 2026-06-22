<?php
/**
 * Template Name: Profil Masjid
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

get_header();

$history   = get_theme_mod( 'alkautsar_profile_history' );
$vision    = get_theme_mod( 'alkautsar_vision' );
$mission   = get_theme_mod( 'alkautsar_mission' );
$chairman  = get_theme_mod( 'alkautsar_dkm_chairman' );
$secretary = get_theme_mod( 'alkautsar_dkm_secretary' );
$treasurer = get_theme_mod( 'alkautsar_dkm_treasurer' );
$imam      = get_theme_mod( 'alkautsar_dkm_imam' );
$extra     = get_theme_mod( 'alkautsar_dkm_extra' );
$hero_img  = get_theme_mod( 'alkautsar_hero_image' );
?>

<header class="page-header">
	<div class="container">
		<h1><?php esc_html_e( 'Profil Masjid Al-Kautsar', 'alkautsar' ); ?></h1>
		<p class="breadcrumb"><?php esc_html_e( 'Mengenal lebih dekat sejarah, visi, misi, dan pengurus masjid.', 'alkautsar' ); ?></p>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">

		<!-- Sejarah -->
		<section class="profile-section">
			<div class="profile-section__inner">
				<div class="profile-section__media">
					<div class="about__media-frame" style="aspect-ratio: 4/3;">
						<?php if ( $hero_img ) : ?>
							<img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php esc_attr_e( 'Masjid Al-Kautsar', 'alkautsar' ); ?>" loading="lazy">
						<?php else : ?>
							<svg viewBox="0 0 600 450" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
								<defs><linearGradient id="sky" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#F8F0DE"/><stop offset="100%" stop-color="#E8D5A8"/></linearGradient></defs>
								<rect width="600" height="450" fill="url(#sky)"/>
								<rect x="80" y="120" width="36" height="280" fill="#3B1E12"/>
								<rect x="484" y="120" width="36" height="280" fill="#3B1E12"/>
								<rect x="180" y="240" width="240" height="160" fill="#3B1E12"/>
								<path d="M200 240 Q300 100 400 240 Z" fill="#D4AF37"/>
								<line x1="300" y1="100" x2="300" y2="60" stroke="#D4AF37" stroke-width="3"/>
								<circle cx="300" cy="55" r="8" fill="#D4AF37"/>
								<path d="M270 400 L270 320 Q300 280 330 320 L330 400 Z" fill="#1F0F08"/>
							</svg>
						<?php endif; ?>
					</div>
				</div>
				<div class="profile-section__content">
					<p class="section-eyebrow"><?php esc_html_e( 'Sejarah Singkat', 'alkautsar' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Perjalanan Masjid Al-Kautsar', 'alkautsar' ); ?></h2>
					<p style="font-size:1.0625rem; line-height:1.75; color: var(--ink);"><?php echo wp_kses_post( wpautop( $history ) ); ?></p>
				</div>
			</div>
		</section>

		<!-- Visi & Misi -->
		<section class="profile-section profile-section--alt">
			<div class="container">
				<div class="section-head section-head--center">
					<p class="section-eyebrow"><?php esc_html_e( 'Arah & Tujuan', 'alkautsar' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Visi & Misi', 'alkautsar' ); ?></h2>
				</div>
				<div class="vision-mission">
					<div class="vision-mission__card vision-mission__card--vision">
						<div class="vision-mission__icon">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/></svg>
						</div>
						<h3><?php esc_html_e( 'Visi', 'alkautsar' ); ?></h3>
						<p><?php echo esc_html( $vision ); ?></p>
					</div>
					<div class="vision-mission__card vision-mission__card--mission">
						<div class="vision-mission__icon">
							<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
						</div>
						<h3><?php esc_html_e( 'Misi', 'alkautsar' ); ?></h3>
						<ul>
							<?php
							$lines = array_filter( array_map( 'trim', explode( "\n", $mission ) ) );
							foreach ( $lines as $line ) {
								echo '<li>' . esc_html( $line ) . '</li>';
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<!-- DKM -->
		<section class="profile-section">
			<div class="container">
				<div class="section-head section-head--center">
					<p class="section-eyebrow"><?php esc_html_e( 'Struktur Pengurus', 'alkautsar' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Dewan Kemakmuran Masjid (DKM)', 'alkautsar' ); ?></h2>
				</div>
				<div class="dkm-grid">
					<?php
					$dkm = array(
						array( 'ketua', $chairman, __( 'Ketua DKM', 'alkautsar' ), 'crown' ),
						array( 'sekretaris', $secretary, __( 'Sekretaris', 'alkautsar' ), 'pen' ),
						array( 'bendahara', $treasurer, __( 'Bendahara', 'alkautsar' ), 'wallet' ),
						array( 'imam', $imam, __( 'Imam Masjid', 'alkautsar' ), 'star' ),
					);
					foreach ( $dkm as $member ) :
						list( $slug, $name, $role, $icon ) = $member;
						if ( ! $name ) { continue; }
						?>
						<div class="dkm-card">
							<div class="dkm-card__avatar">
								<?php echo esc_html( strtoupper( substr( $name, 0, 1 ) ) ); ?>
							</div>
							<h3 class="dkm-card__name"><?php echo esc_html( $name ); ?></h3>
							<p class="dkm-card__role"><?php echo esc_html( $role ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>

				<?php if ( $extra ) : ?>
					<div class="dkm-extra">
						<h3 style="text-align:center; margin-bottom:1rem;"><?php esc_html_e( 'Pengurus Bidang Lain', 'alkautsar' ); ?></h3>
						<?php echo wp_kses_post( wpautop( $extra ) ); ?>
					</div>
				<?php endif; ?>
			</div>
		</section>

	</div>
</main>

<?php get_footer(); ?>
