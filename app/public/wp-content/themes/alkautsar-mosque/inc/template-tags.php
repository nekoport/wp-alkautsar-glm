<?php
/**
 * Custom template tags.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Echo formatted mosque address.
 */
function alkautsar_address() {
	echo wp_kses_post( nl2br( get_theme_mod( 'alkautsar_address', 'Jl. Masjid Al-Kautsar No. 1, Jakarta' ) ) );
}

/**
 * Echo formatted WhatsApp link.
 */
function alkautsar_whatsapp_link( $message = '' ) {
	$number = preg_replace( '/[^0-9]/', '', get_theme_mod( 'alkautsar_whatsapp', '6281234567890' ) );
	$url    = 'https://wa.me/' . $number;
	if ( $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * Posted-on meta.
 */
function alkautsar_posted_on() {
	printf(
		'<span class="posted-on"><time datetime="%1$s">%2$s</time></span>',
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Posted-by meta.
 */
function alkautsar_posted_by() {
	printf(
		'<span class="posted-by">%s</span>',
		esc_html( get_the_author() )
	);
}

/**
 * Entry categories.
 */
function alkautsar_entry_categories() {
	$cats = get_the_category_list( ', ' );
	if ( $cats ) {
		echo '<span class="cat-links">' . wp_kses_post( $cats ) . '</span>';
	}
}

/**
 * Comments link.
 */
function alkautsar_comment_count() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			esc_html__( 'Leave a comment', 'alkautsar' ),
			esc_html__( '1 Comment', 'alkautsar' ),
			esc_html__( '% Comments', 'alkautsar' )
		);
		echo '</span>';
	}
}
