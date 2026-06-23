<?php
/**
 * Register "galeri" Custom Post Type for photo documentation (ALBUM).
 * Setiap post = 1 album kegiatan (mis. "Kerja Bakti Rusunawa")
 * dengan multiple foto di dalamnya.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alkautsar_register_galeri_post_type() {
	$labels = array(
		'name'                  => __( 'Galeri Foto', 'alkautsar' ),
		'singular_name'         => __( 'Album Galeri', 'alkautsar' ),
		'menu_name'             => __( 'Galeri Foto', 'alkautsar' ),
		'add_new'               => __( 'Tambah Album', 'alkautsar' ),
		'add_new_item'          => __( 'Tambah Album Galeri Baru', 'alkautsar' ),
		'edit_item'             => __( 'Edit Album', 'alkautsar' ),
		'new_item'              => __( 'Album Baru', 'alkautsar' ),
		'view_item'             => __( 'Lihat Album', 'alkautsar' ),
		'search_items'          => __( 'Cari Album', 'alkautsar' ),
		'not_found'             => __( 'Tidak ada album ditemukan.', 'alkautsar' ),
		'all_items'             => __( 'Semua Album', 'alkautsar' ),
		'featured_image'        => __( 'Thumbnail Album', 'alkautsar' ),
		'set_featured_image'    => __( 'Pilih thumbnail album', 'alkautsar' ),
		'remove_featured_image' => __( 'Hapus thumbnail', 'alkautsar' ),
		'use_featured_image'    => __( 'Gunakan sebagai thumbnail album', 'alkautsar' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-camera',
		'has_archive'         => true,
		'rewrite'             => array( 'slug' => 'galeri', 'with_front' => false ),
		'exclude_from_search' => false,
		'capability_type'     => 'post',
		'show_in_rest'        => false,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
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
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => false,
		'rewrite'           => array( 'slug' => 'galeri-kategori' ),
	) );
}
add_action( 'init', 'alkautsar_register_galeri_post_type' );

/**
 * Set default title placeholder.
 */
function alkautsar_galeri_default_title( $title, $post ) {
	if ( 'galeri' === $post->post_type ) {
		return __( 'Judul Album / Kegiatan', 'alkautsar' );
	}
	return $title;
}
add_filter( 'enter_title_here', 'alkautsar_galeri_default_title', 10, 2 );

/**
 * Register meta for gallery photos (multiple image IDs).
 */
function alkautsar_register_galeri_meta() {
	register_post_meta( 'galeri', 'alkautsar_galeri_photos', array(
		'type'          => 'string',
		'single'        => true,
		'default'       => '',
		'sanitize_callback' => function( $value ) {
			// Sanitize as comma-separated IDs.
			$ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );
			return implode( ',', $ids );
		},
		'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
		'show_in_rest'  => false,
	) );
}
add_action( 'init', 'alkautsar_register_galeri_meta' );

/**
 * Meta box for uploading multiple photos.
 */
function alkautsar_galeri_photos_meta_box() {
	add_meta_box(
		'alkautsar_galeri_photos',
		__( 'Foto Album (Multiple)', 'alkautsar' ),
		'alkautsar_galeri_photos_meta_box_html',
		'galeri',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'alkautsar_galeri_photos_meta_box' );

function alkautsar_galeri_photos_meta_box_html( $post ) {
	wp_nonce_field( 'alkautsar_galeri_photos_meta', 'alkautsar_galeri_photos_nonce' );
	$photos_raw = get_post_meta( $post->ID, 'alkautsar_galeri_photos', true );
	$photo_ids = array_filter( array_map( 'absint', explode( ',', $photos_raw ) ) );

	wp_enqueue_media();
	?>
	<p style="font-size:13px; color:#666; margin-top:0;">
		<?php esc_html_e( 'Upload atau pilih multiple foto untuk album ini. Foto pertama akan jadi thumbnail utama (jika featured image tidak di-set). Drag untuk reorder.', 'alkautsar' ); ?>
	</p>

	<div id="alkautsar-galeri-photos-container">
		<ul id="alkautsar-galeri-photos-list" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:10px; list-style:none; padding:0; margin:0 0 16px;">
			<?php foreach ( $photo_ids as $id ) : 
				$url = wp_get_attachment_image_src( $id, 'thumbnail' );
				if ( ! $url ) { continue; }
				?>
				<li class="alkautsar-galeri-photo" data-id="<?php echo esc_attr( $id ); ?>" style="position:relative; aspect-ratio:1; border-radius:8px; overflow:hidden; cursor:move;">
					<img src="<?php echo esc_url( $url[0] ); ?>" alt="" style="width:100%; height:100%; object-fit:cover;">
					<button type="button" class="alkautsar-galeri-remove" data-id="<?php echo esc_attr( $id ); ?>" style="position:absolute; top:4px; right:4px; width:24px; height:24px; border-radius:50%; background:rgba(0,0,0,0.7); color:#fff; border:0; cursor:pointer; font-size:14px; line-height:1;">&times;</button>
				</li>
			<?php endforeach; ?>
		</ul>
		<input type="hidden" id="alkautsar_galeri_photos_input" name="alkautsar_galeri_photos" value="<?php echo esc_attr( $photos_raw ); ?>">
		<button type="button" class="button button-primary" id="alkautsar-galeri-add">
			<span class="dashicons dashicons-images-alt2" style="vertical-align:middle;"></span>
			<?php esc_html_e( 'Tambah / Upload Foto', 'alkautsar' ); ?>
		</button>
		<button type="button" class="button" id="alkautsar-galeri-clear" style="margin-left:8px;">
			<?php esc_html_e( 'Hapus Semua', 'alkautsar' ); ?>
		</button>
	</div>

	<style>
		.alkautsar-galeri-photo img { display:block; }
		.alkautsar-galeri-photo:hover .alkautsar-galeri-remove { background:rgba(220, 50, 50, 0.9) !important; }
		#alkautsar-galeri-photos-list:empty::after {
			content: "<?php esc_html_e( 'Belum ada foto. Klik tombol di bawah untuk menambah.', 'alkautsar' ); ?>";
			grid-column: 1 / -1;
			padding: 2rem;
			text-align: center;
			color: #999;
			font-style: italic;
			background: #fafafa;
			border: 1px dashed #ddd;
			border-radius: 8px;
		}
	</style>

	<script>
	jQuery(function($) {
		var frame;
		var $list = $('#alkautsar-galeri-photos-list');
		var $input = $('#alkautsar_galeri_photos_input');

		function updateInput() {
			var ids = [];
			$list.find('.alkautsar-galeri-photo').each(function() {
				ids.push($(this).data('id'));
			});
			$input.val(ids.join(','));
		}

		function addPhoto(id, url) {
			var $item = $(
				'<li class="alkautsar-galeri-photo" data-id="' + id + '" style="position:relative; aspect-ratio:1; border-radius:8px; overflow:hidden; cursor:move;">' +
					'<img src="' + url + '" alt="" style="width:100%; height:100%; object-fit:cover;">' +
					'<button type="button" class="alkautsar-galeri-remove" data-id="' + id + '" style="position:absolute; top:4px; right:4px; width:24px; height:24px; border-radius:50%; background:rgba(0,0,0,0.7); color:#fff; border:0; cursor:pointer; font-size:14px; line-height:1;">&times;</button>' +
				'</li>'
			);
			$list.append($item);
		}

		$('#alkautsar-galeri-add').on('click', function(e) {
			e.preventDefault();
			if (frame) { frame.open(); return; }
			frame = wp.media({
				title: 'Pilih / Upload Foto Album',
				library: { type: 'image' },
				multiple: true
			});
			frame.on('select', function() {
				var selection = frame.state().get('selection');
				selection.each(function(attachment) {
					var att = attachment.toJSON();
					if (att.sizes && att.sizes.thumbnail) {
						addPhoto(att.id, att.sizes.thumbnail.url);
					} else if (att.url) {
						addPhoto(att.id, att.url);
					}
				});
				updateInput();
			});
			frame.open();
		});

		$list.on('click', '.alkautsar-galeri-remove', function(e) {
			e.preventDefault();
			$(this).closest('.alkautsar-galeri-photo').remove();
			updateInput();
		});

		$('#alkautsar-galeri-clear').on('click', function(e) {
			e.preventDefault();
			if (confirm('Hapus semua foto dari album ini?')) {
				$list.empty();
				updateInput();
			}
		});

		// Sortable (drag to reorder) — pakai jQuery UI sortable sederhana.
		$list.on('mousedown', '.alkautsar-galeri-photo', function() {
			$(this).css('opacity', '0.6');
		}).on('mouseup mouseleave', '.alkautsar-galeri-photo', function() {
			$(this).css('opacity', '1');
		});

		// Native HTML5 drag and drop (simple).
		var dragSrc = null;
		$list.on('dragstart', '.alkautsar-galeri-photo', function(e) {
			dragSrc = this;
			e.originalEvent.dataTransfer.effectAllowed = 'move';
			$(this).css('opacity', '0.4');
		}).on('dragend', '.alkautsar-galeri-photo', function(e) {
			$(this).css('opacity', '1');
		}).on('dragover', '.alkautsar-galeri-photo', function(e) {
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
			return false;
		}).on('drop', '.alkautsar-galeri-photo', function(e) {
			e.preventDefault();
			if (dragSrc !== this) {
				var allItems = $list.find('.alkautsar-galeri-photo');
				var srcIdx = allItems.index(dragSrc);
				var tgtIdx = allItems.index(this);
				if (srcIdx < tgtIdx) {
					$(this).after(dragSrc);
				} else {
					$(this).before(dragSrc);
				}
				updateInput();
			}
			return false;
		});
		// Enable draggable.
		$list.find('.alkautsar-galeri-photo').attr('draggable', 'true');
		$list.on('mouseenter', '.alkautsar-galeri-photo', function() {
			$(this).attr('draggable', 'true');
		});
	});
	</script>
	<?php
}

function alkautsar_save_galeri_photos_meta( $post_id ) {
	if ( ! isset( $_POST['alkautsar_galeri_photos_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_galeri_photos_nonce'] ) ), 'alkautsar_galeri_photos_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	if ( isset( $_POST['alkautsar_galeri_photos'] ) ) {
		$value = sanitize_text_field( wp_unslash( $_POST['alkautsar_galeri_photos'] ) );
		$ids = array_filter( array_map( 'absint', explode( ',', $value ) ) );
		update_post_meta( $post_id, 'alkautsar_galeri_photos', implode( ',', $ids ) );

		// Auto-set featured image jika belum ada, ambil dari foto pertama.
		if ( ! has_post_thumbnail( $post_id ) && ! empty( $ids ) ) {
			set_post_thumbnail( $post_id, $ids[0] );
		}
	}
}
add_action( 'save_post_galeri', 'alkautsar_save_galeri_photos_meta' );

/**
 * Add admin column for thumbnail + photo count.
 */
function alkautsar_galeri_admin_columns( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $label ) {
		$new_columns[ $key ] = $label;
		if ( 'title' === $key ) {
			$new_columns['galeri_thumb'] = __( 'Thumbnail', 'alkautsar' );
			$new_columns['galeri_count'] = __( 'Jumlah Foto', 'alkautsar' );
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
	} elseif ( 'galeri_count' === $column ) {
		$photos = get_post_meta( $post_id, 'alkautsar_galeri_photos', true );
		$count = $photos ? count( array_filter( explode( ',', $photos ) ) ) : 0;
		echo '<strong style="color:#B8901E;">' . esc_html( $count ) . '</strong> ' . esc_html__( 'foto', 'alkautsar' );
	}
}
add_action( 'manage_galeri_posts_custom_column', 'alkautsar_galeri_admin_column_content', 10, 2 );

/**
 * Helper: get galeri photos as array of attachment IDs.
 */
function alkautsar_get_galeri_photos( $post_id = null ) {
	if ( null === $post_id ) {
		$post_id = get_the_ID();
	}
	$photos_raw = get_post_meta( $post_id, 'alkautsar_galeri_photos', true );
	if ( ! $photos_raw ) {
		return array();
	}
	return array_filter( array_map( 'absint', explode( ',', $photos_raw ) ) );
}

/**
 * Helper: get galeri albums (for archive page).
 */
function alkautsar_get_galeri_albums( $limit = 12, $category = '' ) {
	$args = array(
		'post_type'      => 'galeri',
		'posts_per_page' => $limit,
		'orderby'        => 'date',
		'order'          => 'DESC',
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
