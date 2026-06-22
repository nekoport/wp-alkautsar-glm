<?php
/**
 * Al-Kautsar Mosque Theme functions
 *
 * @package AlKautsar
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Prevent direct access — OWASP A05:2021 Security Misconfiguration.
}

define( 'ALKAUTSAR_VERSION', '1.0.0' );
define( 'ALKAUTSAR_DIR', get_template_directory() );
define( 'ALKAUTSAR_URI', get_template_directory_uri() );

/**
 * Theme setup: register theme support features.
 */
function alkautsar_setup() {
        // Make theme available for translation.
        load_theme_textdomain( 'alkautsar', ALKAUTSAR_DIR . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', array(
                'height'      => 80,
                'width'       => 320,
                'flex-height' => true,
                'flex-width'  => true,
        ) );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'html5', array(
                'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
        ) );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );

        // Editor styles.
        add_editor_style( 'assets/css/editor-style.css' );

        // Image sizes.
        add_image_size( 'alkautsar-card', 600, 400, true );
        add_image_size( 'alkautsar-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'alkautsar_setup' );

/**
 * Set content width.
 */
function alkautsar_content_width() {
        $GLOBALS['content_width'] = apply_filters( 'alkautsar_content_width', 1200 );
}
add_action( 'after_setup_theme', 'alkautsar_content_width', 0 );

/**
 * Register navigation menus.
 */
function alkautsar_register_menus() {
        register_nav_menus( array(
                'primary' => __( 'Primary Menu', 'alkautsar' ),
                'footer'  => __( 'Footer Menu', 'alkautsar' ),
        ) );
}
add_action( 'init', 'alkautsar_register_menus' );

/**
 * Enqueue scripts and styles.
 */
function alkautsar_scripts() {
        // Google Fonts — Cormorant Garamond (display) + Inter (body) + Amiri (Arabic).
        wp_enqueue_style(
                'alkautsar-fonts',
                'https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@300;400;500;600;700&display=swap',
                array(),
                null
        );

        // Main stylesheet.
        wp_enqueue_style( 'alkautsar-style', get_stylesheet_uri(), array(), ALKAUTSAR_VERSION );
        wp_enqueue_style( 'alkautsar-main', ALKAUTSAR_URI . '/assets/css/main.css', array( 'alkautsar-style' ), ALKAUTSAR_VERSION );

        // Main script.
        wp_enqueue_script( 'alkautsar-main', ALKAUTSAR_URI . '/assets/js/main.js', array(), ALKAUTSAR_VERSION, true );

        // Prayer times script — localised data passed securely.
        wp_enqueue_script( 'alkautsar-prayer', ALKAUTSAR_URI . '/assets/js/prayer-times.js', array(), ALKAUTSAR_VERSION, true );
        wp_localize_script( 'alkautsar-prayer', 'alkautsarPrayer', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'alkautsar_prayer_nonce' ),
                // Jakarta, Indonesia — user can override via Customizer.
                'lat'     => get_theme_mod( 'alkautsar_latitude', '-6.2088' ),
                'lng'     => get_theme_mod( 'alkautsar_longitude', '106.8456' ),
                'tz'      => get_theme_mod( 'alkautsar_timezone', 'Asia/Jakarta' ),
                'method'  => get_theme_mod( 'alkautsar_prayer_method', '20' ), // Kemenag RI
                'i18n'    => array(
                        'fajr'    => __( 'Subuh', 'alkautsar' ),
                        'sunrise' => __( 'Terbit', 'alkautsar' ),
                        'dhuhr'   => __( 'Dzuhur', 'alkautsar' ),
                        'asr'     => __( 'Ashar', 'alkautsar' ),
                        'maghrib' => __( 'Maghrib', 'alkautsar' ),
                        'isha'    => __( 'Isya', 'alkautsar' ),
                        'next'    => __( 'Menuju', 'alkautsar' ),
                        'today'   => __( 'Jadwal Sholat Hari Ini', 'alkautsar' ),
                ),
        ) );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
        }
}
add_action( 'wp_enqueue_scripts', 'alkautsar_scripts' );

/**
 * Register widget areas.
 */
function alkautsar_widgets_init() {
        register_sidebar( array(
                'name'          => __( 'Sidebar', 'alkautsar' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Main sidebar', 'alkautsar' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 1', 'alkautsar' ),
                'id'            => 'footer-1',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 2', 'alkautsar' ),
                'id'            => 'footer-2',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 3', 'alkautsar' ),
                'id'            => 'footer-3',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
}
add_action( 'widgets_init', 'alkautsar_widgets_init' );

/**
 * Fallback menu — used when no menu is assigned.
 */
function alkautsar_fallback_menu() {
        $items = array(
                '/'            => __( 'Beranda', 'alkautsar' ),
                '/berita'      => __( 'Berita & Informasi', 'alkautsar' ),
                '/profil'      => __( 'Profil', 'alkautsar' ),
                '/program'     => __( 'Program', 'alkautsar' ),
                '/donasi'      => __( 'Donasi', 'alkautsar' ),
                '/transparansi' => __( 'Transparansi', 'alkautsar' ),
                '/jadwal-sholat' => __( 'Jadwal Sholat', 'alkautsar' ),
                '/kontak'      => __( 'Kontak', 'alkautsar' ),
        );
        echo '<ul class="menu">';
        foreach ( $items as $url => $label ) {
                echo '<li><a href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . '</a></li>';
        }
        echo '</ul>';
}

/**
 * Custom excerpt length.
 */
function alkautsar_excerpt_length( $length ) {
        return 28;
}
add_filter( 'excerpt_length', 'alkautsar_excerpt_length' );

function alkautsar_excerpt_more( $more ) {
        return '&hellip;';
}
add_filter( 'excerpt_more', 'alkautsar_excerpt_more' );

/**
 * Body classes.
 */
function alkautsar_body_classes( $classes ) {
        if ( ! is_singular() ) {
                $classes[] = 'hfeed';
        }
        if ( ! is_active_sidebar( 'sidebar-1' ) ) {
                $classes[] = 'no-sidebar';
        }
        return $classes;
}
add_filter( 'body_class', 'alkautsar_body_classes' );

/**
 * Include additional theme files.
 */
require ALKAUTSAR_DIR . '/inc/template-tags.php';
require ALKAUTSAR_DIR . '/inc/customizer.php';
require ALKAUTSAR_DIR . '/inc/security.php';
require ALKAUTSAR_DIR . '/inc/events.php';
require ALKAUTSAR_DIR . '/inc/beneficiaries.php';
require ALKAUTSAR_DIR . '/inc/financial-reports.php';
require ALKAUTSAR_DIR . '/inc/programs.php';

/**
 * AJAX: cache prayer times server-side (OWASP: never trust remote API directly on each request).
 */
function alkautsar_ajax_prayer_times() {
        check_ajax_referer( 'alkautsar_prayer_nonce', 'nonce' ); // OWASP A07:2021 — CSRF protection.

        $cache_key = 'alkautsar_prayer_' . gmdate( 'Y-m-d' );
        $cached    = get_transient( $cache_key );

        if ( false !== $cached ) {
                wp_send_json_success( $cached );
        }

        $lat    = isset( $_POST['lat'] ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '-6.2088';
        $lng    = isset( $_POST['lng'] ) ? sanitize_text_field( wp_unslash( $_POST['lng'] ) ) : '106.8456';
        $method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '20';

        // Validate numeric — OWASP A03:2021 Injection prevention.
        if ( ! is_numeric( $lat ) || ! is_numeric( $lng ) || ! is_numeric( $method ) ) {
                wp_send_json_error( array( 'message' => 'Invalid coordinates' ) );
        }

        $url = sprintf(
                'https://api.aladhan.com/v1/timings/%s?latitude=%s&longitude=%s&method=%s',
                gmdate( 'd-m-Y' ),
                $lat,
                $lng,
                $method
        );

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );
        if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => 'Remote API unavailable' ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['data']['timings'] ) ) {
                wp_send_json_error( array( 'message' => 'Malformed response' ) );
        }

        $timings = array_intersect_key(
                $data['data']['timings'],
                array_flip( array( 'Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha' ) )
        );

        set_transient( $cache_key, $timings, DAY_IN_SECONDS );
        wp_send_json_success( $timings );
}
add_action( 'wp_ajax_alkautsar_prayer_times', 'alkautsar_ajax_prayer_times' );
add_action( 'wp_ajax_nopriv_alkautsar_prayer_times', 'alkautsar_ajax_prayer_times' );

/**
 * AJAX: monthly prayer calendar (cached server-side per month).
 */
function alkautsar_ajax_prayer_calendar() {
        check_ajax_referer( 'alkautsar_prayer_nonce', 'nonce' );

        $year   = isset( $_POST['year'] ) ? sanitize_text_field( wp_unslash( $_POST['year'] ) ) : gmdate( 'Y' );
        $month  = isset( $_POST['month'] ) ? sanitize_text_field( wp_unslash( $_POST['month'] ) ) : gmdate( 'n' );
        $lat    = isset( $_POST['lat'] ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '-6.2088';
        $lng    = isset( $_POST['lng'] ) ? sanitize_text_field( wp_unslash( $_POST['lng'] ) ) : '106.8456';
        $method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '20';

        if ( ! is_numeric( $year ) || ! is_numeric( $month ) || ! is_numeric( $lat ) || ! is_numeric( $lng ) || ! is_numeric( $method ) ) {
                wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
        }

        $cache_key = 'alkautsar_prayer_cal_' . $year . '_' . $month . '_' . $lat . '_' . $lng;
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
                wp_send_json_success( $cached );
        }

        $url = sprintf(
                'https://api.aladhan.com/v1/calendar/%d/%d?latitude=%s&longitude=%s&method=%s',
                (int) $year,
                (int) $month,
                $lat,
                $lng,
                $method
        );

        $response = wp_remote_get( $url, array( 'timeout' => 15 ) );
        if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => 'Remote API unavailable' ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
                wp_send_json_error( array( 'message' => 'Malformed response' ) );
        }

        // Simplify payload to reduce size.
        $simplified = array();
        foreach ( $data['data'] as $day ) {
                if ( ! isset( $day['date']['gregorian']['day'] ) || ! isset( $day['timings'] ) ) {
                        continue;
                }
                $simplified[] = array(
                        'day'      => (int) $day['date']['gregorian']['day'],
                        'hijri'    => isset( $day['date']['hijri']['day'] ) ? $day['date']['hijri']['day'] : '',
                        'hijri_month' => isset( $day['date']['hijri']['month']['en'] ) ? $day['date']['hijri']['month']['en'] : '',
                        'timings'  => array_intersect_key(
                                $day['timings'],
                                array_flip( array( 'Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha' ) )
                        ),
                );
        }

        set_transient( $cache_key, $simplified, WEEK_IN_SECONDS );
        wp_send_json_success( $simplified );
}
add_action( 'wp_ajax_alkautsar_prayer_calendar', 'alkautsar_ajax_prayer_calendar' );
add_action( 'wp_ajax_nopriv_alkautsar_prayer_calendar', 'alkautsar_ajax_prayer_calendar' );

/**
 * Add defer to non-critical scripts.
 */
function alkautsar_defer_scripts( $tag, $handle ) {
        $defer = array( 'alkautsar-prayer' );
        if ( in_array( $handle, $defer, true ) ) {
                return str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
}
add_filter( 'script_loader_tag', 'alkautsar_defer_scripts', 10, 2 );

/**
 * Preconnect for Google Fonts — performance optimisation.
 */
function alkautsar_resource_hints( $hints, $relation ) {
        if ( 'preconnect' === $relation ) {
                $hints[] = array(
                        'href' => 'https://fonts.googleapis.com',
                );
                $hints[] = array(
                        'href' => 'https://fonts.gstatic.com',
                        'crossorigin',
                );
                $hints[] = array(
                        'href' => 'https://api.aladhan.com',
                );
        }
        return $hints;
}
add_filter( 'wp_resource_hints', 'alkautsar_resource_hints', 10, 2 );
