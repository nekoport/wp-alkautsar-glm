<?php
/**
 * Register "galeri" Custom Post Type for photo documentation.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alkautsar_register_galeri_post_type() {
	$labels = array(
		'name'                  => __( 'Galeri Foto', 'alkautsar' ),
		'singular_name'         => __( 'Foto', 'alkautsar' ),
		'menu_name'             => __( 'Galeri Foto', 'alkautsar' ),
		'add_new'               => __( 'Tambah Foto', 'alkautsar' ),
		'add_new_item'          => __( 'Tambah Foto Baru', 'alkautsar' ),
		'edit_item'             => __( 'Edit Foto', 'alkautsar' ),
		'new_item'              => __( 'Foto Baru', 'alkautsar' ),
		'view_item'             => __( 'Lihat Foto', 'alkautsar' ),
		'search_items'          => __( 'Cari Foto', 'alkautsar' ),
		'not_found'             => __( 'Tidak ada foto ditemukan.', 'alkautsar' ),
		'all_items'             => __( 'Semua Foto', 'alkautsar' ),
		'featured_image'        => __( 'Foto', 'alkautsar' ),
		'set_featured_image'    => __( 'Pilih foto', 'alkautsar' ),
		'remove_featured_image' => __( 'Hapus foto', 'alkautsar' ),
		'use_featured_image'    => __( 'Gunakan sebagai foto', 'alkautsar' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-camera',
		'has_archive'         => false,
		'rewrite'             => array( 'slug' => 'galeri', 'with_front' => false ),
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'show_in_rest'        => false,
		'supports'            => array( 'title', 'thumbnail', 'excerpt', 'page-attributes' ),
	);

	register_post_type( 'galeri', $args );

	// Register taxonomy for galeri categories.
	register_taxonomy( 'galeri_category', 'galeri', array(
		'labels'            => array(
			'name'              => __( 'Kategori Galeri', 'alkautsar' ),
			'singular_name'     => __( 'Kategori', 'alkautsar' ),
			'menu_name'         => __( 'Kategori', 'alkautsar' ),
			'all_items'         => __( 'Semua Kategori', 'alkautsar' ),
			'edit_item'         => __( 'Edit Kategori', 'alkautsar' ),
			'view_item'         => __( 'Lihat Kategori', 'alkautsar' ),
			'update_item'       => __( 'Update Kategori', 'alkautsar' ),
			'add_new_item'      => __( 'Tambah Kategori Baru', 'alkautsar' ),
			'new_item_name'     => __( 'Nama Kategori Baru', 'alkautsar' ),
			'search_items'      => __( 'Cari Kategori', 'alkautsar' ),
			'parent_item'       => __( 'Kategori Induk', 'alkautsar' ),
			'parent_item_colon' => __( 'Kategori Induk:', 'alkautsar' ),
		),
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => false,
		'rewrite'           => false,
	) );
}
add_action( 'init', 'alkautsar_register_galeri_post_type' );

/**
 * Set default title placeholder.
 */
function alkautsar_galeri_default_title( $title, $post ) {
	if ( 'galeri' === $post->post_type ) {
		return __( 'Judul Foto / Kegiatan', 'alkautsar' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'alkautsar_galeri_default_title', 10, 2 );

/**
 * Add admin column for thumbnail.
 */
function alkautsar_galeri_admin_columns( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$new_columns['galeri_thumb'] = __( 'Foto', 'alkautsar' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_galeri_posts_columns', 'alkautsar_galeri_admin_columns' );

function alkautsar_galeri_admin_column_content( $column, $post_id ) {
	if ( 'galeri_thumb' === $column ) {
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, array( 60, 60 ), array( 'style' => 'border-radius:6px;' ) );
		} else {
			echo '<span style="color:#999; font-style:italic;">No photo</span>';
		}
	}
}
add_action( 'manage_galeri_posts_custom_column', 'alkautsar_galeri_admin_column_content', 10, 2 );

/**
 * Helper: get galeri photos.
 */
function alkautsar_get_galeri_photos( $limit = 12, $category = '' ) {
	$args = array(
		'post_type'      => 'galeri',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => false,
	);
	if ( $category ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'galeri_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	return new WP_Query( $args );
}

/**
 * Helper: get galeri categories.
 */
function alkautsar_get_galeri_categories() {
	return get_terms( array(
		'taxonomy'   => 'galeri_category',
		'hide_empty' => true,
	) );
}
