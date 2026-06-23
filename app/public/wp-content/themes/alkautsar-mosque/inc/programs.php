<?php
/**
 * Register "program" Custom Post Type.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}

function alkautsar_register_program_post_type() {
        $labels = array(
                'name'                  => __( 'Program', 'alkautsar' ),
                'singular_name'         => __( 'Program', 'alkautsar' ),
                'menu_name'             => __( 'Program', 'alkautsar' ),
                'add_new'               => __( 'Tambah Program', 'alkautsar' ),
                'add_new_item'          => __( 'Tambah Program Baru', 'alkautsar' ),
                'edit_item'             => __( 'Edit Program', 'alkautsar' ),
                'new_item'              => __( 'Program Baru', 'alkautsar' ),
                'view_item'             => __( 'Lihat Program', 'alkautsar' ),
                'search_items'          => __( 'Cari Program', 'alkautsar' ),
                'all_items'             => __( 'Semua Program', 'alkautsar' ),
                'featured_image'        => __( 'Gambar Program', 'alkautsar' ),
        );

        $args = array(
                'labels'              => $labels,
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,
                'show_in_rest'        => false, // Disable Gutenberg — pakai Classic Editor.
                'menu_position'       => 5,
                'menu_icon'           => 'dashicons-clipboard',
                'has_archive'         => true,
                'rewrite'             => array( 'slug' => 'program-detail', 'with_front' => false ),
                'exclude_from_search' => false,
                'capability_type'     => 'post',
                'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        );

        register_post_type( 'program', $args );
}
add_action( 'init', 'alkautsar_register_program_post_type' );

function alkautsar_register_program_meta() {
        $fields = array(
                'alkautsar_program_icon'    => array( 'default' => 'book', 'type' => 'string' ),
                'alkautsar_program_schedule'=> array( 'default' => '', 'type' => 'string' ),
                'alkautsar_program_location'=> array( 'default' => '', 'type' => 'string' ),
        );
        foreach ( $fields as $key => $args ) {
                register_post_meta( 'program', $key, array_merge( $args, array(
                        'single'            => true,
                        'sanitize_callback' => 'sanitize_text_field',
                        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
                        'show_in_rest'      => true,
                ) ) );
        }
}
add_action( 'init', 'alkautsar_register_program_meta' );

function alkautsar_program_meta_box() {
        add_meta_box(
                'alkautsar_program_details',
                __( 'Detail Program', 'alkautsar' ),
                'alkautsar_program_meta_box_html',
                'program',
                'side',
                'default'
        );
}
add_action( 'add_meta_boxes', 'alkautsar_program_meta_box' );

function alkautsar_program_meta_box_html( $post ) {
        wp_nonce_field( 'alkautsar_program_meta', 'alkautsar_program_nonce' );
        $icon     = get_post_meta( $post->ID, 'alkautsar_program_icon', true );
        $schedule = get_post_meta( $post->ID, 'alkautsar_program_schedule', true );
        $location = get_post_meta( $post->ID, 'alkautsar_program_location', true );
        ?>
        <p>
                <label for="alkautsar_program_icon"><strong><?php esc_html_e( 'Ikon Program', 'alkautsar' ); ?></strong></label><br>
                <select id="alkautsar_program_icon" name="alkautsar_program_icon" style="width:100%;">
                        <?php
                        $icons = alkautsar_program_icons();
                        foreach ( $icons as $val => $label ) {
                                echo '<option value="' . esc_attr( $val ) . '" ' . selected( $icon, $val, false ) . '>' . esc_html( $label ) . '</option>';
                        }
                        ?>
                </select>
        </p>
        <p>
                <label for="alkautsar_program_schedule"><strong><?php esc_html_e( 'Jadwal', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_program_schedule" name="alkautsar_program_schedule" value="<?php echo esc_attr( $schedule ); ?>" placeholder="<?php esc_attr_e( 'Setiap ba\'da Maghrib', 'alkautsar' ); ?>" style="width:100%;">
        </p>
        <p>
                <label for="alkautsar_program_location"><strong><?php esc_html_e( 'Lokasi', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_program_location" name="alkautsar_program_location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'Aula Masjid', 'alkautsar' ); ?>" style="width:100%;">
        </p>
        <?php
}

function alkautsar_save_program_meta( $post_id ) {
        if ( ! isset( $_POST['alkautsar_program_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_program_nonce'] ) ), 'alkautsar_program_meta' ) ) {
                return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
        if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

        $fields = array( 'alkautsar_program_icon', 'alkautsar_program_schedule', 'alkautsar_program_location' );
        foreach ( $fields as $field ) {
                if ( isset( $_POST[ $field ] ) ) {
                        update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
                }
        }
}
add_action( 'save_post_program', 'alkautsar_save_program_meta' );

function alkautsar_program_icons() {
        return array(
                'book'      => __( 'Buku / Kajian', 'alkautsar' ),
                'graduation'=> __( 'Toga / Pendidikan', 'alkautsar' ),
                'heart'     => __( 'Hati / Sosial', 'alkautsar' ),
                'users'     => __( 'Orang / Komunitas', 'alkautsar' ),
                'cube'      => __( 'Kubus / Pelatihan', 'alkautsar' ),
                'info'      => __( 'Info / Konsultasi', 'alkautsar' ),
                'calendar'  => __( 'Kalender / Acara', 'alkautsar' ),
                'globe'     => __( 'Globe / Dakwah', 'alkautsar' ),
                'giving'    => __( 'Tangan / Wakaf', 'alkautsar' ),
        );
}

function alkautsar_program_icon_svg( $icon ) {
        $paths = array(
                'book'      => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
                'graduation'=> '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
                'heart'     => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
                'users'     => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                'cube'      => '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
                'info'      => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
                'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>',
                'globe'     => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>',
                'giving'    => '<path d="M12 2L2 7v10l10 5 10-5V7L12 2z"/><polyline points="2 7 12 12 22 7"/><line x1="12" y1="22" x2="12" y2="12"/>',
        );
        return isset( $paths[ $icon ] ) ? $paths[ $icon ] : $paths['book'];
}
