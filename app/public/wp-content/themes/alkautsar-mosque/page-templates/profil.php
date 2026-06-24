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

                <section class="profile-section">
                        <div class="profile-section__inner">
                                <div class="profile-section__media">
                                        <?php if ( $hero_img ) : ?>
                                                <div class="about__media-frame" style="aspect-ratio: 4/3;">
                                                        <img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php esc_attr_e( 'Masjid Al-Kautsar', 'alkautsar' ); ?>" loading="lazy">
                                                </div>
                                        <?php endif; ?>
                                </div>
                                <div class="profile-section__content">
                                        <p class="section-eyebrow"><?php esc_html_e( 'Sejarah Singkat', 'alkautsar' ); ?></p>
                                        <h2 class="section-title"><?php esc_html_e( 'Perjalanan Masjid Al-Kautsar', 'alkautsar' ); ?></h2>
                                        <p style="font-size:1.0625rem; line-height:1.75; color: var(--ink);"><?php echo wp_kses_post( wpautop( $history ) ); ?></p>
                                </div>
                        </div>
                </section>

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

                <section class="profile-section">
                        <div class="container">
                                <div class="section-head section-head--center">
                                        <p class="section-eyebrow"><?php esc_html_e( 'Struktur Pengurus', 'alkautsar' ); ?></p>
                                        <h2 class="section-title"><?php esc_html_e( 'Dewan Kemakmuran Masjid (DKM)', 'alkautsar' ); ?></h2>
                                </div>
                                <div class="dkm-grid">
                                        <?php
                                        $dkm_query = alkautsar_get_dkm_members();
                                        $roles = alkautsar_dkm_roles();

                                        if ( $dkm_query->have_posts() ) :
                                                while ( $dkm_query->have_posts() ) : $dkm_query->the_post();
                                                        $role_slug = get_post_meta( get_the_ID(), 'alkautsar_dkm_role', true );
                                                        $role_label = isset( $roles[ $role_slug ] ) ? $roles[ $role_slug ] : $role_slug;
                                                        $bio = get_post_meta( get_the_ID(), 'alkautsar_dkm_bio', true );
                                                        ?>
                                                        <div class="dkm-card">
                                                                <div class="dkm-card__avatar">
                                                                        <?php if ( has_post_thumbnail() ) : ?>
                                                                                <?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
                                                                        <?php else : ?>
                                                                                <span class="dkm-card__avatar-initial"><?php echo esc_html( strtoupper( substr( get_the_title(), 0, 1 ) ) ); ?></span>
                                                                        <?php endif; ?>
                                                                </div>
                                                                <h3 class="dkm-card__name"><?php the_title(); ?></h3>
                                                                <p class="dkm-card__role"><?php echo esc_html( $role_label ); ?></p>
                                                                <?php if ( $bio ) : ?>
                                                                        <p class="dkm-card__bio"><?php echo esc_html( $bio ); ?></p>
                                                                <?php endif; ?>
                                                        </div>
                                                <?php endwhile; wp_reset_postdata();
                                        else :
                                                // Belum ada anggota DKM di database.
                                                ?>
                                                <p style="text-align:center; padding:3rem; color: var(--ink-soft); background: var(--base-alt); border-radius: var(--radius-lg); grid-column:1/-1;">
                                                        <?php esc_html_e( 'Belum ada data pengurus DKM. Admin dapat menambahkannya dari menu "Pengurus DKM" di dashboard.', 'alkautsar' ); ?>
                                                </p>
                                        <?php
                                        endif;
                                        ?>
                                </div>
                        </div>
                </section>

        </div>
</main>

<?php get_footer(); ?>
