<?php
/**
 * Header template.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#3B1E12">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'alkautsar' ); ?></a>

<header id="masthead" class="site-header" role="banner">
	<div class="header-topbar">
		<div class="container header-topbar__inner">
			<div class="header-topbar__contact">
				<?php
				$phone = get_theme_mod( 'alkautsar_phone', '(021) 1234 5678' );
				$email = get_theme_mod( 'alkautsar_email', 'info@masjidal-kautsar.id' );
				if ( $phone ) :
					?>
					<span class="topbar-item">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
						<?php echo esc_html( $phone ); ?>
					</span>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<span class="topbar-item">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
						<?php echo esc_html( $email ); ?>
					</span>
				<?php endif; ?>
			</div>
			<div class="header-topbar__cta">
				<a href="<?php echo esc_url( home_url( '/donasi' ) ); ?>" class="topbar-donate">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
					<?php esc_html_e( 'Donasi Sekarang', 'alkautsar' ); ?>
				</a>
			</div>
		</div>
	</div>

	<div class="header-main">
		<div class="container header-main__inner">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
						<span class="brand-emblem" aria-hidden="true">
							<svg width="48" height="48" viewBox="0 0 64 64" fill="none">
								<circle cx="32" cy="32" r="30" stroke="#D4AF37" stroke-width="1.5"/>
								<path d="M32 8 L36 28 L56 28 L40 40 L46 60 L32 48 L18 60 L24 40 L8 28 L28 28 Z" fill="#D4AF37"/>
							</svg>
						</span>
					</a>
					<?php
				}
				?>
				<div class="site-branding__text">
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php endif; ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
				</div>
			</div>

			<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'alkautsar' ); ?>">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle__bars"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'alkautsar' ); ?></span>
				</button>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => 'alkautsar_fallback_menu',
					'depth'          => 2,
				) );
				?>
			</nav>
		</div>
	</div>
</header>

<div id="content" class="site-content">
