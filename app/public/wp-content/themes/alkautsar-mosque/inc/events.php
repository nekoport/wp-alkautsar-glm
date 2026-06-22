<?php
/**
 * Register "event" Custom Post Type for upcoming mosque activities.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

/**
 * Register the Event post type.
 */
function alkautsar_register_event_post_type() {

        $labels = array(
                'name'                  => __( 'Kegiatan', 'alkautsar' ),
                'singular_name'         => __( 'Kegiatan', 'alkautsar' ),
                'menu_name'             => __( 'Kegiatan', 'alkautsar' ),
                'name_admin_bar'        => __( 'Kegiatan', 'alkautsar' ),
                'add_new'               => __( 'Tambah Kegiatan', 'alkautsar' ),
                'add_new_item'          => __( 'Tambah Kegiatan Baru', 'alkautsar' ),
                'edit_item'             => __( 'Edit Kegiatan', 'alkautsar' ),
                'new_item'              => __( 'Kegiatan Baru', 'alkautsar' ),
                'view_item'             => __( 'Lihat Kegiatan', 'alkautsar' ),
                'view_items'            => __( 'Lihat Semua Kegiatan', 'alkautsar' ),
                'search_items'          => __( 'Cari Kegiatan', 'alkautsar' ),
                'not_found'             => __( 'Tidak ada kegiatan ditemukan.', 'alkautsar' ),
                'not_found_in_trash'    => __( 'Tidak ada kegiatan di tempat sampah.', 'alkautsar' ),
                'all_items'             => __( 'Semua Kegiatan', 'alkautsar' ),
                'featured_image'        => __( 'Gambar Kegiatan', 'alkautsar' ),
                'set_featured_image'    => __( 'Pilih gambar kegiatan', 'alkautsar' ),
                'remove_featured_image' => __( 'Hapus gambar', 'alkautsar' ),
                'use_featured_image'    => __( 'Gunakan sebagai gambar kegiatan', 'alkautsar' ),
        );

        $args = array(
                'labels'              => $labels,
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_admin_bar'   => true,
                'show_in_rest'        => true, // Gutenberg / block editor support.
                'menu_position'       => 5,
                'menu_icon'           => 'dashicons-calendar-alt',
                'has_archive'         => true,
                'rewrite'             => array( 'slug' => 'kegiatan', 'with_front' => false ),
                'exclude_from_search' => false,
                'capability_type'     => 'post',
                'map_meta_cap'        => true,
                'hierarchical'        => false,
                'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author' ),
        );

        register_post_type( 'event', $args );
}
add_action( 'init', 'alkautsar_register_event_post_type' );

/**
 * Register meta fields for events (REST + Customizer friendly).
 */
function alkautsar_register_event_meta() {
        $fields = array(
                'alkautsar_event_date'     => array( 'type' => 'string', 'single' => true, 'default' => '' ),
                'alkautsar_event_time'     => array( 'type' => 'string', 'single' => true, 'default' => '' ),
                'alkautsar_event_end_time' => array( 'type' => 'string', 'single' => true, 'default' => '' ),
                'alkautsar_event_location' => array( 'type' => 'string', 'single' => true, 'default' => '' ),
                'alkautsar_event_speaker'  => array( 'type' => 'string', 'single' => true, 'default' => '' ),
                'alkautsar_event_category' => array( 'type' => 'string', 'single' => true, 'default' => '' ),
        );

        foreach ( $fields as $key => $args ) {
                register_post_meta( 'event', $key, array_merge( $args, array(
                        'sanitize_callback' => 'sanitize_text_field',
                        'auth_callback'     => function () {
                                return current_user_can( 'edit_posts' );
                        },
                        'show_in_rest'      => true,
                ) ) );
        }
}
add_action( 'init', 'alkautsar_register_event_meta' );

/**
 * Add meta box for event fields (classic editor fallback).
 */
function alkautsar_event_meta_box() {
        add_meta_box(
                'alkautsar_event_details',
                __( 'Detail Kegiatan', 'alkautsar' ),
                'alkautsar_event_meta_box_html',
                'event',
                'side',
                'default'
        );
}
add_action( 'add_meta_boxes', 'alkautsar_event_meta_box' );

/**
 * Meta box HTML.
 */
function alkautsar_event_meta_box_html( $post ) {
        wp_nonce_field( 'alkautsar_event_meta', 'alkautsar_event_nonce' );

        $date     = get_post_meta( $post->ID, 'alkautsar_event_date', true );
        $time     = get_post_meta( $post->ID, 'alkautsar_event_time', true );
        $end_time = get_post_meta( $post->ID, 'alkautsar_event_end_time', true );
        $location = get_post_meta( $post->ID, 'alkautsar_event_location', true );
        $speaker  = get_post_meta( $post->ID, 'alkautsar_event_speaker', true );
        $category = get_post_meta( $post->ID, 'alkautsar_event_category', true );

        ?>
        <p>
                <label for="alkautsar_event_date"><strong><?php esc_html_e( 'Tanggal', 'alkautsar' ); ?></strong></label><br>
                <input type="date" id="alkautsar_event_date" name="alkautsar_event_date" value="<?php echo esc_attr( $date ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_event_time"><strong><?php esc_html_e( 'Jam Mulai', 'alkautsar' ); ?></strong></label><br>
                <input type="time" id="alkautsar_event_time" name="alkautsar_event_time" value="<?php echo esc_attr( $time ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_event_end_time"><strong><?php esc_html_e( 'Jam Selesai', 'alkautsar' ); ?></strong></label><br>
                <input type="time" id="alkautsar_event_end_time" name="alkautsar_event_end_time" value="<?php echo esc_attr( $end_time ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_event_location"><strong><?php esc_html_e( 'Lokasi', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_event_location" name="alkautsar_event_location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'Aula Masjid', 'alkautsar' ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_event_speaker"><strong><?php esc_html_e( 'Pembicara / Imam', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_event_speaker" name="alkautsar_event_speaker" value="<?php echo esc_attr( $speaker ); ?>" placeholder="<?php esc_attr_e( 'Ustadz Ahmad Fauzi', 'alkautsar' ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_event_category"><strong><?php esc_html_e( 'Kategori', 'alkautsar' ); ?></strong></label><br>
                <select id="alkautsar_event_category" name="alkautsar_event_category" style="width:100%;">
                        <option value=""><?php esc_html_e( '— Pilih —', 'alkautsar' ); ?></option>
                        <?php
                        $cats = array(
                                'kajian'      => __( 'Kajian', 'alkautsar' ),
                                'sosial'      => __( 'Sosial', 'alkautsar' ),
                                'pendidikan'  => __( 'Pendidikan', 'alkautsar' ),
                                'ibadah'      => __( 'Ibadah', 'alkautsar' ),
                                'remaja'      => __( 'Remaja', 'alkautsar' ),
                                'khusus'      => __( 'Khusus', 'alkautsar' ),
                        );
                        foreach ( $cats as $val => $label ) {
                                echo '<option value="' . esc_attr( $val ) . '" ' . selected( $category, $val, false ) . '>' . esc_html( $label ) . '</option>';
                        }
                        ?>
                </select>
        </p>
        <?php
}

/**
 * Save meta box.
 */
function alkautsar_save_event_meta( $post_id ) {
        if ( ! isset( $_POST['alkautsar_event_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_event_nonce'] ) ), 'alkautsar_event_meta' ) ) {
                return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
        }

        $fields = array(
                'alkautsar_event_date',
                'alkautsar_event_time',
                'alkautsar_event_end_time',
                'alkautsar_event_location',
                'alkautsar_event_speaker',
                'alkautsar_event_category',
        );

        foreach ( $fields as $field ) {
                if ( isset( $_POST[ $field ] ) ) {
                        update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
                }
        }
}
add_action( 'save_post_event', 'alkautsar_save_event_meta' );

/**
 * Helper: get upcoming events (sorted by date ascending).
 */
function alkautsar_get_upcoming_events( $limit = 4 ) {
        $today = gmdate( 'Y-m-d' );

        $query = new WP_Query( array(
                'post_type'      => 'event',
                'posts_per_page' => $limit,
                'meta_key'       => 'alkautsar_event_date',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',
                'meta_query'     => array(
                        array(
                                'key'     => 'alkautsar_event_date',
                                'value'   => $today,
                                'compare' => '>=',
                                'type'    => 'DATE',
                        ),
                ),
                'no_found_rows'  => true,
        ) );

        return $query;
}

/**
 * Helper: format event date into indonesian readable string.
 */
function alkautsar_format_event_date( $date_str ) {
        if ( ! $date_str ) { return ''; }
        $ts    = strtotime( $date_str );
        $days  = array( 'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu' );
        $months = array( 1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' );
        return $days[ gmdate( 'w', $ts ) ] . ', ' . gmdate( 'j', $ts ) . ' ' . $months[ (int) gmdate( 'n', $ts ) ] . ' ' . gmdate( 'Y', $ts );
}

/**
 * Helper: short month name for calendar date badge.
 */
function alkautsar_event_month_short( $date_str ) {
        if ( ! $date_str ) { return ''; }
        $ts     = strtotime( $date_str );
        $months = array( 1 => 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGT', 'SEP', 'OKT', 'NOV', 'DES' );
        return $months[ (int) gmdate( 'n', $ts ) ];
}

/**
 * Helper: get event category label.
 */
function alkautsar_event_category_label( $slug ) {
        $map = array(
                'kajian'     => __( 'Kajian', 'alkautsar' ),
                'sosial'     => __( 'Sosial', 'alkautsar' ),
                'pendidikan' => __( 'Pendidikan', 'alkautsar' ),
                'ibadah'     => __( 'Ibadah', 'alkautsar' ),
                'remaja'     => __( 'Remaja', 'alkautsar' ),
                'khusus'     => __( 'Khusus', 'alkautsar' ),
        );
        return isset( $map[ $slug ] ) ? $map[ $slug ] : $slug;
}
