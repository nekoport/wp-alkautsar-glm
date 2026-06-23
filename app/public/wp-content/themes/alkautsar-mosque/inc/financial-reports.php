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
                'alkautsar_report_period'    => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_year'      => array( 'default' => gmdate( 'Y' ), 'type' => 'string' ),
                'alkautsar_report_month'     => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_date'      => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_pdf'       => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_income'    => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_expense'   => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_summary'   => array( 'default' => '', 'type' => 'string' ),
                'alkautsar_report_items'     => array( 'default' => '', 'type' => 'string' ),
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

        $period    = get_post_meta( $post->ID, 'alkautsar_report_period', true );
        $year      = get_post_meta( $post->ID, 'alkautsar_report_year', true );
        $month     = get_post_meta( $post->ID, 'alkautsar_report_month', true );
        $date      = get_post_meta( $post->ID, 'alkautsar_report_date', true );
        $pdf       = get_post_meta( $post->ID, 'alkautsar_report_pdf', true );
        $income    = get_post_meta( $post->ID, 'alkautsar_report_income', true );
        $expense   = get_post_meta( $post->ID, 'alkautsar_report_expense', true );
        $summary   = get_post_meta( $post->ID, 'alkautsar_report_summary', true );
        $items     = get_post_meta( $post->ID, 'alkautsar_report_items', true );

        wp_enqueue_media();
        ?>
        <style>
                .alkautsar-finance-field { display:none; }
                .alkautsar-finance-field.is-active { display:block; }
                .alkautsar-finance-helper {
                        background: #F1E7D2;
                        border-left: 4px solid #D4AF37;
                        padding: 10px 14px;
                        border-radius: 4px;
                        font-size: 13px;
                        margin: 12px 0;
                        color: #3B1E12;
                }
                .alkautsar-finance-helper strong { color: #B8901E; }
                .alkautsar-finance-input-display {
                        background: #fff;
                        border: 1px solid #ddd;
                        padding: 8px 12px;
                        border-radius: 4px;
                        font-weight: 600;
                        color: #3B1E12;
                        font-size: 14px;
                        min-height: 20px;
                }
                .alkautsar-finance-input-display.empty { color:#999; font-weight:normal; }
                .alkautsar-finance-input-display.income { color:#128C7E; }
                .alkautsar-finance-input-display.expense { color:#B85C00; }
        </style>

        <div class="alkautsar-finance-helper">
                <strong>Tips:</strong> Untuk masjid kecil, pilih <em>Harian</em> atau <em>Mingguan</em> agar pelaporan lebih ringan. Pilih <em>Bulanan</em> untuk laporan komprehensif. Isi total pemasukan & pengeluaran saja sudah cukup. PDF opsional.
        </div>

        <p>
                <label for="alkautsar_report_period"><strong><?php esc_html_e( 'Tipe Periode Laporan', 'alkautsar' ); ?></strong></label><br>
                <select id="alkautsar_report_period" name="alkautsar_report_period" style="width:100%;">
                        <?php
                        $periods = array(
                                'daily'     => __( 'Harian (per hari)', 'alkautsar' ),
                                'weekly'    => __( 'Mingguan (per pekan)', 'alkautsar' ),
                                'monthly'   => __( 'Bulanan (per bulan)', 'alkautsar' ),
                                'quarterly' => __( 'Triwulan (Q1-Q4)', 'alkautsar' ),
                                'yearly'    => __( 'Tahunan', 'alkautsar' ),
                                'ramadhan'  => __( 'Khusus Ramadhan', 'alkautsar' ),
                                'qurban'    => __( 'Khusus Idul Adha / Qurban', 'alkautsar' ),
                                'event'     => __( 'Khusus Acara (Akbar, Maulid, dll)', 'alkautsar' ),
                        );
                        foreach ( $periods as $val => $label ) {
                                echo '<option value="' . esc_attr( $val ) . '" ' . selected( $period, $val, false ) . '>' . esc_html( $label ) . '</option>';
                        }
                        ?>
                </select>
        </p>

        <!-- Field: Tanggal Harian -->
        <p class="alkautsar-finance-field" data-period="daily">
                <label for="alkautsar_report_date"><strong><?php esc_html_e( 'Tanggal Laporan', 'alkautsar' ); ?></strong></label><br>
                <input type="date" id="alkautsar_report_date" name="alkautsar_report_date" value="<?php echo esc_attr( $date ); ?>" style="width:100%;">
        </p>

        <!-- Field: Bulanan / Quarterly / Ramadhan / Qurban -->
        <p class="alkautsar-finance-field" data-period="monthly quarterly ramadhan qurban event">
                <label for="alkautsar_report_month"><strong><?php esc_html_e( 'Bulan', 'alkautsar' ); ?></strong></label><br>
                <select id="alkautsar_report_month" name="alkautsar_report_month" style="width:100%;">
                        <option value=""><?php esc_html_e( '— Pilih Bulan —', 'alkautsar' ); ?></option>
                        <?php
                        $months = array(
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                        );
                        foreach ( $months as $val => $label ) {
                                echo '<option value="' . esc_attr( $val ) . '" ' . selected( $month, $val, false ) . '>' . esc_html( $label ) . '</option>';
                        }
                        ?>
                </select>
        </p>

        <!-- Field: Tahun (selalu tampil) -->
        <p>
                <label for="alkautsar_report_year"><strong><?php esc_html_e( 'Tahun', 'alkautsar' ); ?></strong></label><br>
                <input type="number" min="2000" max="2100" id="alkautsar_report_year" name="alkautsar_report_year" value="<?php echo esc_attr( $year ); ?>" style="width:100%;">
        </p>

        <hr style="border:0; border-top:1px solid #eee; margin:1.5rem 0;">

        <!-- Pemasukan -->
        <p>
                <label for="alkautsar_report_income"><strong><?php esc_html_e( 'Total Pemasukan (Rp)', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_report_income_raw" name="alkautsar_report_income" value="<?php echo esc_attr( $income ); ?>" placeholder="500000" style="width:100%;" inputmode="numeric">
                <span class="alkautsar-finance-input-display <?php echo $income ? 'income' : 'empty'; ?>" id="alkautsar_report_income_display">
                        <?php echo $income ? esc_html( alkautsar_format_rupiah( $income ) ) : esc_html__( 'Otomatis tampil sebagai: Rp 0', 'alkautsar' ); ?>
                </span>
        </p>

        <!-- Pengeluaran -->
        <p>
                <label for="alkautsar_report_expense"><strong><?php esc_html_e( 'Total Pengeluaran (Rp)', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_report_expense_raw" name="alkautsar_report_expense" value="<?php echo esc_attr( $expense ); ?>" placeholder="450000" style="width:100%;" inputmode="numeric">
                <span class="alkautsar-finance-input-display <?php echo $expense ? 'expense' : 'empty'; ?>" id="alkautsar_report_expense_display">
                        <?php echo $expense ? esc_html( alkautsar_format_rupiah( $expense ) ) : esc_html__( 'Otomatis tampil sebagai: Rp 0', 'alkautsar' ); ?>
                </span>
        </p>

        <!-- Saldo otomatis -->
        <p style="background:#F8F1E4; padding:12px 16px; border-radius:6px;">
                <strong style="color:#3B1E12; font-size:14px;"><?php esc_html_e( 'Saldo (Pemasukan - Pengeluaran):', 'alkautsar' ); ?></strong><br>
                <span id="alkautsar_report_balance" style="font-size:20px; font-weight:700; color:#D4AF37; font-family:'Cormorant Garamond', serif;">
                        <?php
                        $bal = (float) preg_replace( '/[^0-9]/', '', $income ) - (float) preg_replace( '/[^0-9]/', '', $expense );
                        echo esc_html( alkautsar_format_rupiah( $bal ) );
                        ?>
                </span>
        </p>

        <!-- Detail item (opsional) -->
        <p>
                <label for="alkautsar_report_items"><strong><?php esc_html_e( 'Rincian Item (opsional, satu per baris)', 'alkautsar' ); ?></strong></label><br>
                <textarea id="alkautsar_report_items" name="alkautsar_report_items" rows="4" style="width:100%;" placeholder="<?php esc_attr_e( "Contoh:\n- Infak jamaah Jumat: Rp 2.500.000\n- Listrik & air: Rp 800.000\n- Santunan dhuafa: Rp 1.500.000", 'alkautsar' ); ?>"><?php echo esc_textarea( $items ); ?></textarea>
        </p>

        <!-- Ringkasan -->
        <p>
                <label for="alkautsar_report_summary"><strong><?php esc_html_e( 'Ringkasan / Catatan (opsional)', 'alkautsar' ); ?></strong></label><br>
                <textarea id="alkautsar_report_summary" name="alkautsar_report_summary" rows="3" style="width:100%;" placeholder="<?php esc_attr_e( 'Tuliskan catatan singkat tentang laporan ini.', 'alkautsar' ); ?>"><?php echo esc_textarea( $summary ); ?></textarea>
        </p>

        <!-- PDF Upload -->
        <p>
                <label for="alkautsar_report_pdf"><strong><?php esc_html_e( 'File PDF Laporan (opsional)', 'alkautsar' ); ?></strong></label><br>
                <input type="text" id="alkautsar_report_pdf" name="alkautsar_report_pdf" value="<?php echo esc_attr( $pdf ); ?>" placeholder="<?php esc_attr_e( 'URL file PDF (klik tombol di bawah)', 'alkautsar' ); ?>" style="width:100%;">
                <button type="button" class="button button-secondary" id="alkautsar_report_pdf_btn" style="margin-top:0.5rem;">
                        <span class="dashicons dashicons-pdf" style="vertical-align:middle;"></span>
                        <?php esc_html_e( 'Pilih / Upload PDF', 'alkautsar' ); ?>
                </button>
        </p>

        <script>
        jQuery(function($) {
                // Show/hide fields based on period.
                function toggleFinanceFields() {
                        var period = $('#alkautsar_report_period').val();
                        $('.alkautsar-finance-field').each(function() {
                                var periods = ($(this).data('period') || '').split(/\s+/);
                                if (periods.indexOf(period) !== -1) {
                                        $(this).addClass('is-active');
                                } else {
                                        $(this).removeClass('is-active');
                                }
                        });
                }
                $('#alkautsar_report_period').on('change', toggleFinanceFields);
                toggleFinanceFields();

                // Format rupiah display.
                function formatRupiah(angka) {
                        angka = String(angka).replace(/[^0-9]/g, '');
                        if (!angka) return 'Rp 0';
                        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
                }
                function updateDisplay(inputId, displayId, type) {
                        var val = $('#' + inputId).val();
                        var $disp = $('#' + displayId);
                        if (val) {
                                $disp.text(formatRupiah(val)).removeClass('empty').addClass(type);
                        } else {
                                $disp.text('Otomatis tampil sebagai: Rp 0').addClass('empty').removeClass(type);
                        }
                }
                function updateBalance() {
                        var inc = parseFloat(String($('#alkautsar_report_income_raw').val()).replace(/[^0-9]/g, '')) || 0;
                        var exp = parseFloat(String($('#alkautsar_report_expense_raw').val()).replace(/[^0-9]/g, '')) || 0;
                        var bal = inc - exp;
                        $('#alkautsar_report_balance').text('Rp ' + bal.toLocaleString('id-ID'));
                }
                $('#alkautsar_report_income_raw').on('input', function() {
                        updateDisplay('alkautsar_report_income_raw', 'alkautsar_report_income_display', 'income');
                        updateBalance();
                });
                $('#alkautsar_report_expense_raw').on('input', function() {
                        updateDisplay('alkautsar_report_expense_raw', 'alkautsar_report_expense_display', 'expense');
                        updateBalance();
                });

                // PDF upload.
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
                'alkautsar_report_month',
                'alkautsar_report_date',
                'alkautsar_report_pdf',
                'alkautsar_report_income',
                'alkautsar_report_expense',
        );
        foreach ( $fields as $field ) {
                if ( isset( $_POST[ $field ] ) ) {
                        update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
                }
        }
        // Textarea fields.
        if ( isset( $_POST['alkautsar_report_summary'] ) ) {
                update_post_meta( $post_id, 'alkautsar_report_summary', sanitize_textarea_field( wp_unslash( $_POST['alkautsar_report_summary'] ) ) );
        }
        if ( isset( $_POST['alkautsar_report_items'] ) ) {
                update_post_meta( $post_id, 'alkautsar_report_items', sanitize_textarea_field( wp_unslash( $_POST['alkautsar_report_items'] ) ) );
        }
}
add_action( 'save_post_financial_report', 'alkautsar_save_financial_report_meta' );

/**
 * Get human-readable period label.
 */
function alkautsar_get_period_label( $period, $month = '', $year = '', $date = '' ) {
        $months = array(
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        );
        $month_label = isset( $months[ $month ] ) ? $months[ $month ] : '';
        $year_label  = $year ? $year : '';

        switch ( $period ) {
                case 'daily':
                        if ( $date ) {
                                $ts = strtotime( $date );
                                return gmdate( 'j', $ts ) . ' ' . $months[ gmdate( 'm', $ts ) ] . ' ' . gmdate( 'Y', $ts );
                        }
                        return __( 'Harian', 'alkautsar' ) . ' ' . $year_label;
                case 'weekly':
                        return __( 'Mingguan', 'alkautsar' ) . ' ' . $month_label . ' ' . $year_label;
                case 'monthly':
                        return $month_label . ' ' . $year_label;
                case 'quarterly':
                        return __( 'Triwulan', 'alkautsar' ) . ' ' . $year_label;
                case 'yearly':
                        return __( 'Tahun', 'alkautsar' ) . ' ' . $year_label;
                case 'ramadhan':
                        return __( 'Ramadhan', 'alkautsar' ) . ' ' . $year_label;
                case 'qurban':
                        return __( 'Idul Adha', 'alkautsar' ) . ' ' . $year_label;
                case 'event':
                        return __( 'Acara', 'alkautsar' ) . ' ' . $month_label . ' ' . $year_label;
                default:
                        return $year_label;
        }
}

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
