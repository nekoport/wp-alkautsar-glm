<?php
/**
 * Register "dkm_member" Custom Post Type for DKM (Dewan Kemakmuran Masjid).
 *
 * Setiap anggota DKM = satu post dengan:
 *   - Title: nama lengkap
 *   - Featured Image: foto
 *   - Meta: role (ketua, sekretaris, bendahara, imam, lainnya)
 *   - Meta: bio (deskripsi singkat)
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register DKM Member post type.
 */
function alkautsar_register_dkm_post_type() {
	$labels = array(
		'name'                  => __( 'Pengurus DKM', 'alkautsar' ),
		'singular_name'         => __( 'Anggota DKM', 'alkautsar' ),
		'menu_name'             => __( 'Pengurus DKM', 'alkautsar' ),
		'add_new'               => __( 'Tambah Anggota', 'alkautsar' ),
		'add_new_item'          => __( 'Tambah Anggota DKM Baru', 'alkautsar' ),
		'edit_item'             => __( 'Edit Anggota', 'alkautsar' ),
		'new_item'              => __( 'Anggota Baru', 'alkautsar' ),
		'view_item'             => __( 'Lihat Anggota', 'alkautsar' ),
		'search_items'          => __( 'Cari Anggota', 'alkautsar' ),
		'not_found'             => __( 'Tidak ada anggota ditemukan.', 'alkautsar' ),
		'all_items'             => __( 'Semua Anggota', 'alkautsar' ),
		'featured_image'        => __( 'Foto Anggota', 'alkautsar' ),
		'set_featured_image'    => __( 'Pilih foto anggota', 'alkautsar' ),
		'remove_featured_image' => __( 'Hapus foto', 'alkautsar' ),
		'use_featured_image'    => __( 'Gunakan sebagai foto anggota', 'alkautsar' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-groups',
		'has_archive'         => false,
		'rewrite'             => false,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'show_in_rest'        => false,
		'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
	);

	register_post_type( 'dkm_member', $args );
}
add_action( 'init', 'alkautsar_register_dkm_post_type' );

/**
 * Register meta fields.
 */
function alkautsar_register_dkm_meta() {
	$fields = array(
		'alkautsar_dkm_role' => array( 'default' => 'lainnya', 'type' => 'string' ),
		'alkautsar_dkm_bio'  => array( 'default' => '', 'type' => 'string' ),
	);
	foreach ( $fields as $key => $args ) {
		register_post_meta( 'dkm_member', $key, array_merge( $args, array(
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		) ) );
	}
}
add_action( 'init', 'alkautsar_register_dkm_meta' );

/**
 * Meta box for DKM member details.
 */
function alkautsar_dkm_meta_box() {
	add_meta_box(
		'alkautsar_dkm_details',
		__( 'Detail Anggota DKM', 'alkautsar' ),
		'alkautsar_dkm_meta_box_html',
		'dkm_member',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'alkautsar_dkm_meta_box' );

function alkautsar_dkm_meta_box_html( $post ) {
	wp_nonce_field( 'alkautsar_dkm_meta', 'alkautsar_dkm_nonce' );
	$role = get_post_meta( $post->ID, 'alkautsar_dkm_role', true );
	$bio  = get_post_meta( $post->ID, 'alkautsar_dkm_bio', true );
	?>
	<p>
		<label for="alkautsar_dkm_role"><strong><?php esc_html_e( 'Jabatan', 'alkautsar' ); ?></strong></label><br>
		<select id="alkautsar_dkm_role" name="alkautsar_dkm_role" style="width:100%;">
			<?php
			$roles = alkautsar_dkm_roles();
			foreach ( $roles as $val => $label ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( $role, $val, false ) . '>' . esc_html( $label ) . '</option>';
			}
			?>
		</select>
	</p>
	<p>
		<label for="alkautsar_dkm_bio"><strong><?php esc_html_e( 'Bio Singkat', 'alkautsar' ); ?></strong></label><br>
		<textarea id="alkautsar_dkm_bio" name="alkautsar_dkm_bio" rows="4" style="width:100%;" placeholder="<?php esc_attr_e( 'Lulusan Al-Azhar Kairo. Mengajar kitab tafsir & fiqih.', 'alkautsar' ); ?>"><?php echo esc_textarea( $bio ); ?></textarea>
	</p>
	<p style="font-style:italic; color:#666; font-size:12px;">
		<?php esc_html_e( 'Upload foto anggota di kotak "Foto Anggota" di sidebar kanan (Featured Image).', 'alkautsar' ); ?>
	</p>
	<?php
}

function alkautsar_save_dkm_meta( $post_id ) {
	if ( ! isset( $_POST['alkautsar_dkm_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_dkm_nonce'] ) ), 'alkautsar_dkm_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	if ( isset( $_POST['alkautsar_dkm_role'] ) ) {
		update_post_meta( $post_id, 'alkautsar_dkm_role', sanitize_text_field( wp_unslash( $_POST['alkautsar_dkm_role'] ) ) );
	}
	if ( isset( $_POST['alkautsar_dkm_bio'] ) ) {
		update_post_meta( $post_id, 'alkautsar_dkm_bio', sanitize_textarea_field( wp_unslash( $_POST['alkautsar_dkm_bio'] ) ) );
	}
}
add_action( 'save_post_dkm_member', 'alkautsar_save_dkm_meta' );

/**
 * Get list of DKM roles.
 */
function alkautsar_dkm_roles() {
	return array(
		'ketua'      => __( 'Ketua DKM', 'alkautsar' ),
		'wakil'      => __( 'Wakil Ketua', 'alkautsar' ),
		'sekretaris' => __( 'Sekretaris', 'alkautsar' ),
		'bendahara'  => __( 'Bendahara', 'alkautsar' ),
		'imam'       => __( 'Imam Masjid', 'alkautsar' ),
		'bidang'     => __( 'Kepala Bidang', 'alkautsar' ),
		'pengurus'   => __( 'Pengurus', 'alkautsar' ),
		'lainnya'    => __( 'Lainnya', 'alkautsar' ),
	);
}

/**
 * Get all DKM members (sorted by menu order, then title).
 */
function alkautsar_get_dkm_members( $limit = -1 ) {
	return new WP_Query( array(
		'post_type'      => 'dkm_member',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	) );
}

/**
 * Add admin column for role (so admin can see role at glance).
 */
function alkautsar_dkm_admin_columns( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$new_columns['dkm_role'] = __( 'Jabatan', 'alkautsar' );
			$new_columns['dkm_photo'] = __( 'Foto', 'alkautsar' );
		}
	}
	return $new_columns;
}
add_filter( 'manage_dkm_member_posts_columns', 'alkautsar_dkm_admin_columns' );

function alkautsar_dkm_admin_column_content( $column, $post_id ) {
	if ( 'dkm_role' === $column ) {
		$role = get_post_meta( $post_id, 'alkautsar_dkm_role', true );
		$roles = alkautsar_dkm_roles();
		echo esc_html( isset( $roles[ $role ] ) ? $roles[ $role ] : $role );
	} elseif ( 'dkm_photo' === $column ) {
		if ( has_post_thumbnail( $post_id ) ) {
			echo get_the_post_thumbnail( $post_id, array( 50, 50 ), array( 'style' => 'border-radius:50%;' ) );
		} else {
			echo '<span style="color:#999; font-style:italic;">No photo</span>';
		}
	}
}
add_action( 'manage_dkm_member_posts_custom_column', 'alkautsar_dkm_admin_column_content', 10, 2 );

/**
 * Set default placeholder for title.
 */
function alkautsar_dkm_default_title( $title, $post ) {
	if ( 'dkm_member' === $post->post_type ) {
		return __( 'Nama Anggota DKM', 'alkautsar' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'alkautsar_dkm_default_title', 10, 2 );
