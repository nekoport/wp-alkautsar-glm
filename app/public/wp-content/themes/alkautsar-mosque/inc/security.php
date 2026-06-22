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
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'Permissions-Policy: geolocation=(self), microphone=(), camera=()' );
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
