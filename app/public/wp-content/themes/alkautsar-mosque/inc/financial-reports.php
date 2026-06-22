<?php
/**
 * Register "financial_report" Custom Post Type.
 * Each post = one PDF report (upload via media library).
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function alkautsar_register_financial_report_post_type() {
	$labels = array(
		'name'                  => __( 'Laporan Keuangan', 'alkautsar' ),
		'singular_name'         => __( 'Laporan Keuangan', 'alkautsar' ),
		'menu_name'             => __( 'Laporan Keuangan', 'alkautsar' ),
		'add_new'               => __( 'Tambah Laporan', 'alkautsar' ),
		'add_new_item'          => __( 'Tambah Laporan Baru', 'alkautsar' ),
		'edit_item'             => __( 'Edit Laporan', 'alkautsar' ),
		'new_item'              => __( 'Laporan Baru', 'alkautsar' ),
		'view_item'             => __( 'Lihat Laporan', 'alkautsar' ),
		'search_items'          => __( 'Cari Laporan', 'alkautsar' ),
		'all_items'             => __( 'Semua Laporan', 'alkautsar' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-chart-area',
		'has_archive'         => false,
		'rewrite'             => false,
		'exclude_from_search' => false,
		'capability_type'     => 'post',
		'show_in_rest'        => false,
		'supports'            => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'financial_report', $args );
}
add_action( 'init', 'alkautsar_register_financial_report_post_type' );

function alkautsar_register_financial_report_meta() {
	$fields = array(
		'alkautsar_report_period' => array( 'default' => '', 'type' => 'string' ),
		'alkautsar_report_year'   => array( 'default' => gmdate( 'Y' ), 'type' => 'string' ),
		'alkautsar_report_pdf'    => array( 'default' => '', 'type' => 'string' ),
		'alkautsar_report_income' => array( 'default' => '', 'type' => 'string' ),
		'alkautsar_report_expense'=> array( 'default' => '', 'type' => 'string' ),
		'alkautsar_report_summary'=> array( 'default' => '', 'type' => 'string' ),
	);
	foreach ( $fields as $key => $args ) {
		register_post_meta( 'financial_report', $key, array_merge( $args, array(
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
		) ) );
	}
}
add_action( 'init', 'alkautsar_register_financial_report_meta' );

function alkautsar_financial_report_meta_box() {
	add_meta_box(
		'alkautsar_financial_report_details',
		__( 'Detail Laporan Keuangan', 'alkautsar' ),
		'alkautsar_financial_report_meta_box_html',
		'financial_report',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'alkautsar_financial_report_meta_box' );

function alkautsar_financial_report_meta_box_html( $post ) {
	wp_nonce_field( 'alkautsar_financial_report_meta', 'alkautsar_financial_report_nonce' );

	$period  = get_post_meta( $post->ID, 'alkautsar_report_period', true );
	$year    = get_post_meta( $post->ID, 'alkautsar_report_year', true );
	$pdf     = get_post_meta( $post->ID, 'alkautsar_report_pdf', true );
	$income  = get_post_meta( $post->ID, 'alkautsar_report_income', true );
	$expense = get_post_meta( $post->ID, 'alkautsar_report_expense', true );
	$summary = get_post_meta( $post->ID, 'alkautsar_report_summary', true );

	wp_enqueue_media();
	?>
	<p>
		<label for="alkautsar_report_period"><strong><?php esc_html_e( 'Periode Laporan', 'alkautsar' ); ?></strong></label><br>
		<select id="alkautsar_report_period" name="alkautsar_report_period" style="width:100%;">
			<?php
			$periods = array(
				''       => __( '— Tahunan —', 'alkautsar' ),
				'Q1'     => __( 'Q1 (Jan – Mar)', 'alkautsar' ),
				'Q2'     => __( 'Q2 (Apr – Jun)', 'alkautsar' ),
				'Q3'     => __( 'Q3 (Jul – Sep)', 'alkautsar' ),
				'Q4'     => __( 'Q4 (Okt – Des)', 'alkautsar' ),
				'Ramadhan' => __( 'Bulan Ramadhan', 'alkautsar' ),
				'Qurban' => __( 'Idul Adha / Qurban', 'alkautsar' ),
			);
			foreach ( $periods as $val => $label ) {
				echo '<option value="' . esc_attr( $val ) . '" ' . selected( $period, $val, false ) . '>' . esc_html( $label ) . '</option>';
			}
			?>
		</select>
	</p>
	<p>
		<label for="alkautsar_report_year"><strong><?php esc_html_e( 'Tahun', 'alkautsar' ); ?></strong></label><br>
		<input type="number" min="2000" max="2100" id="alkautsar_report_year" name="alkautsar_report_year" value="<?php echo esc_attr( $year ); ?>" style="width:100%;">
	</p>
	<p>
		<label for="alkautsar_report_income"><strong><?php esc_html_e( 'Total Pemasukan (Rp, angka saja)', 'alkautsar' ); ?></strong></label><br>
		<input type="text" id="alkautsar_report_income" name="alkautsar_report_income" value="<?php echo esc_attr( $income ); ?>" placeholder="50000000" style="width:100%;">
	</p>
	<p>
		<label for="alkautsar_report_expense"><strong><?php esc_html_e( 'Total Pengeluaran (Rp, angka saja)', 'alkautsar' ); ?></strong></label><br>
		<input type="text" id="alkautsar_report_expense" name="alkautsar_report_expense" value="<?php echo esc_attr( $expense ); ?>" placeholder="45000000" style="width:100%;">
	</p>
	<p>
		<label for="alkautsar_report_summary"><strong><?php esc_html_e( 'Ringkasan (opsional)', 'alkautsar' ); ?></strong></label><br>
		<textarea id="alkautsar_report_summary" name="alkautsar_report_summary" rows="3" style="width:100%;" placeholder="<?php esc_attr_e( 'Pemasukan didominasi donasi infak jamaah, pengeluaran utama untuk operasional masjid & santunan dhuafa.', 'alkautsar' ); ?>"><?php echo esc_textarea( $summary ); ?></textarea>
	</p>
	<p>
		<label for="alkautsar_report_pdf"><strong><?php esc_html_e( 'File PDF Laporan', 'alkautsar' ); ?></strong></label><br>
		<input type="text" id="alkautsar_report_pdf" name="alkautsar_report_pdf" value="<?php echo esc_attr( $pdf ); ?>" placeholder="<?php esc_attr_e( 'URL file PDF (klik tombol di bawah)', 'alkautsar' ); ?>" style="width:100%;">
		<button type="button" class="button button-secondary" id="alkautsar_report_pdf_btn" style="margin-top:0.5rem;">
			<?php esc_html_e( 'Pilih / Upload PDF', 'alkautsar' ); ?>
		</button>
	</p>
	<script>
		jQuery(function($) {
			$('#alkautsar_report_pdf_btn').on('click', function(e) {
				e.preventDefault();
				var frame = wp.media({
					title: 'Pilih File PDF Laporan',
					library: { type: 'application/pdf' },
					multiple: false
				});
				frame.on('select', function() {
					var att = frame.state().get('selection').first().toJSON();
					$('#alkautsar_report_pdf').val(att.url);
				});
				frame.open();
			});
		});
	</script>
	<?php
}

function alkautsar_save_financial_report_meta( $post_id ) {
	if ( ! isset( $_POST['alkautsar_financial_report_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['alkautsar_financial_report_nonce'] ) ), 'alkautsar_financial_report_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

	$fields = array(
		'alkautsar_report_period',
		'alkautsar_report_year',
		'alkautsar_report_pdf',
		'alkautsar_report_income',
		'alkautsar_report_expense',
	);
	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		}
	}
	// Summary pakai sanitize_textarea_field.
	if ( isset( $_POST['alkautsar_report_summary'] ) ) {
		update_post_meta( $post_id, 'alkautsar_report_summary', sanitize_textarea_field( wp_unslash( $_POST['alkautsar_report_summary'] ) ) );
	}
}
add_action( 'save_post_financial_report', 'alkautsar_save_financial_report_meta' );

/**
 * Get financial reports sorted by year (desc) then period.
 */
function alkautsar_get_financial_reports( $limit = 12 ) {
	return new WP_Query( array(
		'post_type'      => 'financial_report',
		'posts_per_page' => $limit,
		'no_found_rows'  => true,
		'meta_key'       => 'alkautsar_report_year',
		'orderby'        => 'meta_value_num',
		'order'          => 'DESC',
	) );
}

/**
 * Format Rupiah.
 */
function alkautsar_format_rupiah( $angka ) {
	$angka = preg_replace( '/[^0-9]/', '', (string) $angka );
	if ( '' === $angka ) { return 'Rp 0'; }
	return 'Rp ' . number_format( (float) $angka, 0, ',', '.' );
}
