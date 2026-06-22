<?php
/**
 * Register "beneficiary" Custom Post Type for transparency.
 * Categories: yatim (anak yatim), dhuafa (fakir miskin), janda (janda).
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alkautsar_register_beneficiary_post_type() {
	$labels = array(
		'name'                  => __( 'Penerima Manfaat', 'alkautsar' ),
		'singular_name'         => __( 'Penerima Manfaat', 'alkautsar' ),
		'menu_name'             => __( 'Penerima Manfaat', 'alkautsar' ),
		'add_new'               => __( 'Tambah Data', 'alkautsar' ),
		'add_new_item'          => __( 'Tambah Penerima Manfaat', 'alkautsar' ),
		'edit_item'             => __( 'Edit Data', 'alkautsar' ),
		'new_item'              => __( 'Data Baru', 'alkautsar' ),
		'view_item'             => __( 'Lihat Data', 'alkautsar' ),
		'search_items'          => __( 'Cari Penerima Manfaat', 'alkautsar' ),
		'not_found'             => __( 'Tidak ada data.', 'alkautsar' ),
		'all_items'             => __( 'Semua Penerima Manfaat', 'alkautsar' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false, // Tidak publik (privacy — data sensitif).
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-groups',
		'has_archive'         => false,
		'rewrite'             => false,
		'exclude_from_search' => true,
		'capability_type'     => 'post',
		'show_in_rest'        => false, // Disable Gutenberg — pakai classic meta box.
		'supports'            => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'beneficiary', $args );
}
add_action( 'init', 'alkautsar_register_beneficiary_post_type' );

function alkautsar_register_beneficiary_meta() {
	$fields = array(
		'alkautsar_beneficiary_category' => array(
			'type'    => 'string',
			'default' => 'yatim',
		),
		'alkautsar_beneficiary_age' => array(
			'type'    => 'string',
			'default' => '',
		),
		'alkautsar_beneficiary_note' => array(
			'type'    => 'string',
			'default' => '',
		),
		'alkautsar_beneficiary_aid' => array(
			'type'    => 'string',
			'default' => '',
		),
	);

	foreach ( $fields as $key => $args ) {
		register_post_meta( 'beneficiary', $key, array_merge( $args, array(
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		) ) );
	}
}
add_action( 'init', 'alkautsar_register_beneficiary_meta' );

function alkautsar_beneficiary_meta_box() {
	add_meta_box(
		'alkautsar_beneficiary_details',
		__( 'Detail Penerima Manfaat', 'alkautsar' ),
		'alkautsar_beneficiary_meta_box_html',
		'beneficiary',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'alkautsar_beneficiary_meta_box' );

function alkautsar_beneficiary_meta_box_html( $post ) {
	wp_nonce_field( 'alkautsar_beneficiary_meta', 'alkautsar_beneficiary_nonce' );

	$category = get_post_meta( $post->ID, 'alkautsar_beneficiary_category', true );
	$age      = get_post_meta( $post->ID, 'alkautsar_beneficiary_age', true );
	$note     = get_post_meta( $post->ID, 'alkautsar_beneficiary_note', true );
	$aid      = get_post_meta( $post->ID, 'alkautsar_beneficiary_aid', true );

	?>
	<p>
		<label for="alkautsar_beneficiary_category"><strong><?php esc_html_e( 'Kategori', 'alkautsar' ); ?></strong></label><br>
		<select id="alkautsar_beneficiary_category" name="alkautsar_beneficiary_category" style="width:100%;">
			<?php
			$cats = alkautsar_beneficiary_categories();
			foreach ( $cats as $val => $label ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( $category, $val, false ) . '>' . esc_html( $label ) . '</option>';
			}
			?>
		</select>
	</p>
	<p>
		<label for="alkautsar_beneficiary_age"><strong><?php esc_html_e( 'Usia (tahun)', 'alkautsar' ); ?></strong></label><br>
		<input type="number" min="0" max="120" id="alkautsar_beneficiary_age" name="alkautsar_beneficiary_age" value="<?php echo esc_attr( $age ); ?>" style="width:100%;">
	</p>
	<p>
		<label for="alkautsar_beneficiary_aid"><strong><?php esc_html_e( 'Bantuan yang Diterima', 'alkautsar' ); ?></strong></label><br>
		<input type="text" id="alkautsar_beneficiary_aid" name="alkautsar_beneficiary_aid" value="<?php echo esc_attr( $aid ); ?>" placeholder="<?php esc_attr_e( 'Santunan bulanan Rp 300.000', 'alkautsar' ); ?>" style="width:100%;">
	</p>
	<p>
		<label for="alkautsar_beneficiary_note"><strong><?php esc_html_e( 'Catatan (opsional)', 'alkautsar' ); ?></strong></label><br>
		<textarea id="alkautsar_beneficiary_note" name="alkautsar_beneficiary_note" rows="3" style="width:100%;" placeholder="<?php esc_attr_e( 'Yatim piatu, kedua orang tua telah wafat', 'alkautsar' ); ?>"><?php echo esc_textarea( $note ); ?></textarea>
	</p>
	<p style="font-style:italic; color:#666;">
		<?php esc_html_e( 'Catatan privasi: Nama boleh inisial (contoh: "A." atau "Anak Yatim A") untuk melindungi identitas.', 'alkautsar' ); ?>
	</p>
	<?php
}

function alkautsar_save_beneficiary_meta( $post_id ) {
	if ( ! isset( $_POST['alkautsar_beneficiary_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_beneficiary_nonce'] ) ), 'alkautsar_beneficiary_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	$fields = array(
		'alkautsar_beneficiary_category',
		'alkautsar_beneficiary_age',
		'alkautsar_beneficiary_note',
		'alkautsar_beneficiary_aid',
	);
	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
}
add_action( 'save_post_beneficiary', 'alkautsar_save_beneficiary_meta' );

/**
 * Beneficiary categories.
 */
function alkautsar_beneficiary_categories() {
	return array(
		'yatim'   => __( 'Anak Yatim', 'alkautsar' ),
		'dhuafa'  => __( 'Dhuafa / Fakir Miskin', 'alkautsar' ),
		'janda'   => __( 'Janda', 'alkautsar' ),
		'lanjut_usia' => __( 'Lanjut Usia', 'alkautsar' ),
		'santri'  => __( 'Santri', 'alkautsar' ),
	);
}

/**
 * Get beneficiary counts grouped by category.
 */
function alkautsar_get_beneficiary_counts() {
	$counts = array(
		'yatim'       => 0,
		'dhuafa'      => 0,
		'janda'       => 0,
		'lanjut_usia' => 0,
		'santri'      => 0,
	);
	$query = new WP_Query( array(
		'post_type'      => 'beneficiary',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'meta_key'       => 'alkautsar_beneficiary_category',
	) );
	foreach ( $query->posts as $p ) {
		$cat = get_post_meta( $p->ID, 'alkautsar_beneficiary_category', true );
		if ( isset( $counts[ $cat ] ) ) {
			$counts[ $cat ]++;
		}
	}
	return $counts;
}

/**
 * Get beneficiaries by category.
 */
function alkautsar_get_beneficiaries( $category = '', $limit = -1 ) {
	$args = array(
		'post_type'      => 'beneficiary',
		'posts_per_page' => $limit,
		'no_found_rows'  => true,
		'meta_key'       => 'alkautsar_beneficiary_category',
		'orderby'        => 'title',
		'order'          => 'ASC',
	);
	if ( $category ) {
		$args['meta_query'] = array(
			array(
				'key'   => 'alkautsar_beneficiary_category',
				'value' => $category,
			),
		);
	}
	return new WP_Query( $args );
}
