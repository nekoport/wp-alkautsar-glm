<?php
/**
 * Security hardening per OWASP Top 10.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Disable XML-RPC — OWASP A06:2021 Vulnerable Components.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove version info from head — OWASP A05:2021 Security Misconfiguration.
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * Send security headers.
 */
function alkautsar_security_headers() {
        if ( headers_sent() ) {
                return;
        }
        header( 'X-Content-Type-Options: nosniff' );
        header( 'X-Frame-Options: SAMEORIGIN' );
        header( 'X-XSS-Protection: 1; mode=block' );
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );
        header( 'Permissions-Policy: geolocation=(self), microphone=(), camera=()' );
        // Strict-Transport-Security (HSTS) — only if HTTPS.
        if ( is_ssl() ) {
                header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
        }
        // Content-Security-Policy — restrict external resources.
        // 'unsafe-eval' dihapus (tidak dibutuhkan WordPress core).
        // 'unsafe-inline' dipertahankan karena WordPress core masih menggunakan
        // inline scripts secara ekstensif (admin bar, wp_localize_script, dll).
        // Migrasi ke nonce-based CSP memerlukan hook ke wp_head/wp_footer yang
        // kompleks dan berisiko break plugin pihak ketiga.
        header( "Content-Security-Policy: default-src 'self'; " .
                "script-src 'self' 'unsafe-inline' https://api.aladhan.com; " .
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
                "font-src 'self' https://fonts.gstatic.com data:; " .
                "img-src 'self' data: https: blob:; " .
                "connect-src 'self' https://api.aladhan.com; " .
                "frame-src 'self' https://www.openstreetmap.org; " .
                "object-src 'none'; " .
                "base-uri 'self'; " .
                "upgrade-insecure-requests"
        );
}
add_action( 'send_headers', 'alkautsar_security_headers' );

/**
 * Disable file editing in admin.
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
        define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Sanitise and escape all outputs — use esc_* family everywhere in templates.
 * This file documents the policy; actual escaping happens in each template.
 */

/**
 * Strict comment sanitisation.
 */
add_filter( 'pre_comment_content', 'wp_kses_post' );

/**
 * ════════════════════════════════════════════════════════════════
 * HIDE AUTHOR — Anti Bruteforce Protection
 * ════════════════════════════════════════════════════════════════
 * Menampilkan author username di postingan publik berbahaya karena
 * membuka peluang bruteforce attack. Username = setengah dari kredensial login.
 * Solusi: sembunyikan author dari semua tampilan publik + disable author archive.
 */

// 1. Remove author link from REST API (headless attack vector).
add_filter( 'rest_prepare_post', function( $response, $post, $request ) {
        $data = $response->get_data();
        if ( isset( $data['author'] ) ) {
                unset( $data['author'] );
        }
        $response->set_data( $data );
        return $response;
}, 10, 3 );

add_filter( 'rest_prepare_page', function( $response, $post, $request ) {
        $data = $response->get_data();
        if ( isset( $data['author'] ) ) {
                unset( $data['author'] );
        }
        $response->set_data( $data );
        return $response;
}, 10, 3 );

// 2. Disable author archive — redirect ke beranda.
add_action( 'template_redirect', function() {
        if ( is_author() ) {
                wp_safe_redirect( home_url(), 301 );
                exit;
        }
} );

// 3. Remove author query from REST API (block ?author=1 enumeration).
add_filter( 'rest_query_vars', function( $valid_vars ) {
        return array_diff( $valid_vars, array( 'author', 'author_name' ) );
} );

// 4. Block ?author=N URL parameter enumeration.
add_action( 'init', function() {
        if ( isset( $_GET['author'] ) && ! is_admin() ) {
                wp_safe_redirect( home_url(), 301 );
                exit;
        }
} );

// 5. Remove author from oEmbed data.
add_filter( 'oembed_response_data', function( $data ) {
        if ( isset( $data['author_name'] ) ) {
                unset( $data['author_name'] );
        }
        if ( isset( $data['author_url'] ) ) {
                unset( $data['author_url'] );
        }
        return $data;
}, 10, 1 );

// 6. Hide author in feed (RSS).
add_filter( 'the_author', '__return_empty_string' );
add_filter( 'the_author_display_name', '__return_empty_string' );
