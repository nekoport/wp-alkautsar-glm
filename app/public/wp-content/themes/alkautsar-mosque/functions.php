<?php
/**
 * Al-Kautsar Mosque Theme functions
 *
 * @package AlKautsar
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Prevent direct access — OWASP A05:2021 Security Misconfiguration.
}

define( 'ALKAUTSAR_VERSION', '1.0.0' );
define( 'ALKAUTSAR_DIR', get_template_directory() );
define( 'ALKAUTSAR_URI', get_template_directory_uri() );

/**
 * Theme setup: register theme support features.
 */
function alkautsar_setup() {
        // Make theme available for translation.
        load_theme_textdomain( 'alkautsar', ALKAUTSAR_DIR . '/languages' );

        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'custom-logo', array(
                'height'      => 80,
                'width'       => 320,
                'flex-height' => true,
                'flex-width'  => true,
        ) );
        add_theme_support( 'customize-selective-refresh-widgets' );
        add_theme_support( 'html5', array(
                'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
        ) );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'align-wide' );

        // Editor styles.
        add_editor_style( 'assets/css/editor-style.css' );

        // Image sizes.
        add_image_size( 'alkautsar-card', 600, 400, true );
        add_image_size( 'alkautsar-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'alkautsar_setup' );

/**
 * Set content width.
 */
function alkautsar_content_width() {
        $GLOBALS['content_width'] = apply_filters( 'alkautsar_content_width', 1200 );
}
add_action( 'after_setup_theme', 'alkautsar_content_width', 0 );

/**
 * Register navigation menus.
 */
function alkautsar_register_menus() {
        register_nav_menus( array(
                'primary' => __( 'Primary Menu', 'alkautsar' ),
                'footer'  => __( 'Footer Menu', 'alkautsar' ),
        ) );
}
add_action( 'init', 'alkautsar_register_menus' );

/**
 * Enqueue scripts and styles.
 */
function alkautsar_scripts() {
        // Google Fonts — Cormorant Garamond (display) + Inter (body) + Amiri (Arabic).
        wp_enqueue_style(
                'alkautsar-fonts',
                'https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@300;400;500;600;700&display=swap',
                array(),
                null
        );

        // Main stylesheet.
        wp_enqueue_style( 'alkautsar-style', get_stylesheet_uri(), array(), ALKAUTSAR_VERSION );
        wp_enqueue_style( 'alkautsar-main', ALKAUTSAR_URI . '/assets/css/main.css', array( 'alkautsar-style' ), ALKAUTSAR_VERSION );

        // Main script.
        wp_enqueue_script( 'alkautsar-main', ALKAUTSAR_URI . '/assets/js/main.js', array(), ALKAUTSAR_VERSION, true );

        // Prayer times script — localised data passed securely.
        wp_enqueue_script( 'alkautsar-prayer', ALKAUTSAR_URI . '/assets/js/prayer-times.js', array(), ALKAUTSAR_VERSION, true );
        wp_localize_script( 'alkautsar-prayer', 'alkautsarPrayer', array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'alkautsar_prayer_nonce' ),
                // Jakarta, Indonesia — user can override via Customizer.
                'lat'     => get_theme_mod( 'alkautsar_latitude', get_theme_mod( 'alkautsar_map_lat', '-6.2088' ) ),
                'lng'     => get_theme_mod( 'alkautsar_longitude', get_theme_mod( 'alkautsar_map_lng', '106.8456' ) ),
                'tz'      => get_theme_mod( 'alkautsar_timezone', 'Asia/Jakarta' ),
                'method'  => get_theme_mod( 'alkautsar_prayer_method', '20' ), // Kemenag RI
                'i18n'    => array(
                        'fajr'    => __( 'Subuh', 'alkautsar' ),
                        'sunrise' => __( 'Terbit', 'alkautsar' ),
                        'dhuhr'   => __( 'Dzuhur', 'alkautsar' ),
                        'asr'     => __( 'Ashar', 'alkautsar' ),
                        'maghrib' => __( 'Maghrib', 'alkautsar' ),
                        'isha'    => __( 'Isya', 'alkautsar' ),
                        'next'    => __( 'Menuju', 'alkautsar' ),
                        'today'   => __( 'Jadwal Sholat Hari Ini', 'alkautsar' ),
                ),
        ) );

        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
                wp_enqueue_script( 'comment-reply' );
        }
}
add_action( 'wp_enqueue_scripts', 'alkautsar_scripts' );

/**
 * Register widget areas.
 */
function alkautsar_widgets_init() {
        register_sidebar( array(
                'name'          => __( 'Sidebar', 'alkautsar' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Main sidebar', 'alkautsar' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 1', 'alkautsar' ),
                'id'            => 'footer-1',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 2', 'alkautsar' ),
                'id'            => 'footer-2',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
        register_sidebar( array(
                'name'          => __( 'Footer Column 3', 'alkautsar' ),
                'id'            => 'footer-3',
                'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="footer-widget-title">',
                'after_title'   => '</h3>',
        ) );
}
add_action( 'widgets_init', 'alkautsar_widgets_init' );

/**
 * Fallback menu — used when no menu is assigned.
 */
function alkautsar_fallback_menu() {
        $items = array(
                '/'            => __( 'Beranda', 'alkautsar' ),
                '/berita'      => __( 'Berita & Informasi', 'alkautsar' ),
                '/profil'      => __( 'Profil', 'alkautsar' ),
                '/program'     => __( 'Program', 'alkautsar' ),
                '/donasi'      => __( 'Donasi', 'alkautsar' ),
                '/transparansi' => __( 'Transparansi', 'alkautsar' ),
                '/jadwal-sholat' => __( 'Jadwal Sholat', 'alkautsar' ),
                '/kontak'      => __( 'Kontak', 'alkautsar' ),
        );
        echo '<ul class="menu">';
        foreach ( $items as $url => $label ) {
                echo '<li><a href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . '</a></li>';
        }
        echo '</ul>';
}

/**
 * Custom excerpt length.
 */
function alkautsar_excerpt_length( $length ) {
        return 28;
}
add_filter( 'excerpt_length', 'alkautsar_excerpt_length' );

function alkautsar_excerpt_more( $more ) {
        return '&hellip;';
}
add_filter( 'excerpt_more', 'alkautsar_excerpt_more' );

/**
 * Body classes.
 */
function alkautsar_body_classes( $classes ) {
        if ( ! is_singular() ) {
                $classes[] = 'hfeed';
        }
        if ( ! is_active_sidebar( 'sidebar-1' ) ) {
                $classes[] = 'no-sidebar';
        }
        return $classes;
}
add_filter( 'body_class', 'alkautsar_body_classes' );

/**
 * Include additional theme files.
 */
require ALKAUTSAR_DIR . '/inc/template-tags.php';
require ALKAUTSAR_DIR . '/inc/customizer.php';
require ALKAUTSAR_DIR . '/inc/security.php';
require ALKAUTSAR_DIR . '/inc/events.php';
require ALKAUTSAR_DIR . '/inc/financial-reports.php';
require ALKAUTSAR_DIR . '/inc/programs.php';
require ALKAUTSAR_DIR . '/inc/dkm.php';
require ALKAUTSAR_DIR . '/inc/galeri.php';
require ALKAUTSAR_DIR . '/inc/user-roles.php';
require ALKAUTSAR_DIR . '/inc/admin-settings.php';

/**
 * Register Block Pattern category for mosque-specific patterns.
 */
function alkautsar_register_pattern_category() {
        register_block_pattern_category(
                'alkautsar',
                array(
                        'label' => __( 'Masjid Al-Kautsar', 'alkautsar' ),
                )
        );
}
add_action( 'init', 'alkautsar_register_pattern_category' );

/**
 * ════════════════════════════════════════════════════════════════
 * CLASSIC EDITOR (TinyMCE) — untuk pengurus awam yang tidak ngerti kode
 * ════════════════════════════════════════════════════════════════
 * Disable Gutenberg (block editor) dan pakai Classic Editor yang
 * mirip Microsoft Word — toolbar atas dengan Bold, Italic, alignment, dll.
 */

// 1. Disable Gutenberg untuk SEMUA post type (posts, pages, CPTs).
add_filter( 'use_block_editor_for_post_type', '__return_false' );
add_filter( 'use_block_editor_for_post', '__return_false' );

// 2. Remove Gutenberg stylesheet dari front-end (tidak dipakai lagi).
add_action( 'wp_enqueue_scripts', function() {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-block-style' );
}, 20 );

/**
 * 3. Aktifkan toolbar TinyMCE baris kedua (kitchen sink) secara default.
 *    Baris kedua berisi: format, style, font color, background color,
 *    indent/outdent, undo/redo, clear formatting, dll.
 */
add_filter( 'tiny_mce_before_init', function( $init, $editor_id = '' ) {
        // Tampilkan toolbar baris kedua (default-nya tersembunyi di balik tombol "Toolbar Toggle").
        $init['wordpress_adv_hidden'] = false;

        // Safe whitelist — izinkan HTML standar + class attribute untuk pattern masjid,
        // TAPI blokir tag berbahaya: <script>, <iframe>, <object>, <embed>, <form>, dll.
        // (Sebelumnya: '*[*]' yang mengizinkan SEMUA tag — HIGH RISK XSS)
        $init['valid_elements']          = 'a[href|target|rel|class|title|id],abbr[class|title],b[class],blockquote[class|cite],br,button[class|type|data-*],caption[class],code[class],dd[class],del[class|datetime],div[class|id|style|data-*],dl[class],dt[class],em[class],figcaption[class],figure[class],h1[class|id],h2[class|id],h3[class|id],h4[class|id],h5[class|id],h6[class|id],hr[class],img[class|src|alt|width|height|loading],ins[class|datetime],li[class],mark[class],ol[class|type|start],p[class|id|style],pre[class],s[class],small[class],span[class|id|style|data-*],strong[class],sub[class],sup[class],table[class],tbody[class],td[class|colspan|rowspan],tfoot[class],th[class|colspan|rowspan|scope],thead[class],tr[class],u[class],ul[class|type],video[class|src|width|height|controls],source[src|type]';
        $init['extended_valid_elements'] = 'a[href|target|rel|class|title|id],abbr[class|title],b[class],blockquote[class|cite],br,button[class|type|data-*],caption[class],code[class],dd[class],del[class|datetime],div[class|id|style|data-*],dl[class],dt[class],em[class],figcaption[class],figure[class],h1[class|id],h2[class|id],h3[class|id],h4[class|id],h5[class|id],h6[class|id],hr[class],img[class|src|alt|width|height|loading],ins[class|datetime],li[class],mark[class],ol[class|type|start],p[class|id|style],pre[class],s[class],small[class],span[class|id|style|data-*],strong[class],sub[class],sup[class],table[class],tbody[class],td[class|colspan|rowspan],tfoot[class],th[class|colspan|rowspan|scope],thead[class],tr[class],u[class],ul[class|type],video[class|src|width|height|controls],source[src|type]';

        // Eksplisit blokir tag berbahaya.
        $init['invalid_elements'] = 'script,iframe,object,embed,form,input,textarea,select,option,link,meta,style,base,applet,param';

        // Tambah styling body editor.
        if ( ! isset( $init['body_class'] ) ) {
                $init['body_class'] = '';
        }
        $init['body_class'] .= ' alkautsar-classic-editor';

        // Pastikan paste dari Word bersih.
        $init['paste_remove_styles']         = true;
        $init['paste_remove_spans']          = true;
        $init['paste_strip_class_attributes'] = 'all';

        return $init;
}, 10, 2 );

/**
 * 4. Konfigurasi tombol toolbar baris pertama.
 *    Urutan: paragraph dropdown, bold, italic, underline, strikethrough,
 *    align left/center/right/justify, bullet list, numbered list, blockquote,
 *    link, image, remove format, code view.
 */
add_filter( 'mce_buttons', function( $buttons, $editor_id = '' ) {
        $buttons = array(
                'formatselect',
                'bold',
                'italic',
                'underline',
                'strikethrough',
                'bullist',
                'numlist',
                'blockquote',
                'alignleft',
                'aligncenter',
                'alignright',
                'alignjustify',
                'link',
                'unlink',
                'wp_more',
                'image',
                'hr',
                'fullscreen',
                'wp_adv', // tombol toggle baris kedua
        );
        return $buttons;
}, 10, 2 );

/**
 * 5. Konfigurasi tombol toolbar baris kedua.
 *    Urutan: style select, font color, background color, sub/superscript,
 *    outdent/indent, charmap (special chars), undo/redo, cleanup, code view.
 */
add_filter( 'mce_buttons_2', function( $buttons, $editor_id = '' ) {
        $buttons = array(
                'styleselect',
                'forecolor',
                'backcolor',
                'subscript',
                'superscript',
                'outdent',
                'indent',
                'pastetext',
                'charmap',
                'wp_help',
                'undo',
                'redo',
                'removeformat',
                'code',
        );
        return $buttons;
}, 10, 2 );

/**
 * 6. Tambah custom style formats ke dropdown "Style" di baris kedua.
 *    Pengurus tinggal pilih: "Pengumuman", "Ayat Arab", "Quote Emas", dll.
 *    Shortcut untuk styling masjid tanpa perlu ingat class name.
 */
add_filter( 'tiny_mce_before_init', function( $init ) {
        $style_formats = array(
                array(
                        'title'    => __( '📢 Pengumuman Penting', 'alkautsar' ),
                        'block'    => 'div',
                        'classes'  => 'akp-pengumuman',
                        'wrapper'  => true,
                ),
                array(
                        'title'    => __( '🕌 Kotak Ayat', 'alkautsar' ),
                        'block'    => 'div',
                        'classes'  => 'akp-ayat',
                        'wrapper'  => true,
                ),
                array(
                        'title'    => __( 'ℹ️ Info Box', 'alkautsar' ),
                        'block'    => 'div',
                        'classes'  => 'akp-info-box',
                        'wrapper'  => true,
                ),
                array(
                        'title'    => __( '💛 Banner Donasi', 'alkautsar' ),
                        'block'    => 'div',
                        'classes'  => 'akp-banner-donasi',
                        'wrapper'  => true,
                ),
                array(
                        'title'    => __( 'Teks Arab (RTL)', 'alkautsar' ),
                        'inline'   => 'span',
                        'classes'  => 'akp-ayat-arabic',
                        'wrapper'  => false,
                ),
                array(
                        'title'    => __( 'Tombol Emas', 'alkautsar' ),
                        'inline'   => 'span',
                        'classes'  => 'btn btn--gold',
                        'wrapper'  => false,
                ),
                array(
                        'title'    => __( 'Heading Emas', 'alkautsar' ),
                        'block'    => 'h3',
                        'classes'  => 'akp-section-eyebrow',
                        'wrapper'  => false,
                ),
        );

        // Merge dengan style formats bawaan.
        $existing = isset( $init['style_formats'] ) ? json_decode( $init['style_formats'], true ) : array();
        if ( ! is_array( $existing ) ) {
                $existing = array();
        }
        $init['style_formats'] = wp_json_encode( array_merge( $existing, $style_formats ) );

        return $init;
} );

/**
 * 7. Hide welcome panel & default WordPress dashboard widgets yang membingungkan.
 *    Supaya pengurus langsung fokus ke menu utama.
 */
add_action( 'admin_init', function() {
        // Hide welcome panel.
        update_user_meta( get_current_user_id(), 'show_welcome_panel', 0 );
} );

/**
 * 8. Custom help tab di editor — tampilkan panduan singkat.
 */
add_action( 'admin_head-post-new.php', 'alkautsar_editor_help' );
add_action( 'admin_head-post.php', 'alkautsar_editor_help' );
function alkautsar_editor_help() {
        $screen = get_current_screen();
        if ( ! $screen ) { return; }
        if ( ! in_array( $screen->base, array( 'post', 'edit' ), true ) ) { return; }

        $screen->add_help_tab( array(
                'id'      => 'alkautsar-help',
                'title'   => __( 'Panduan Masjid', 'alkautsar' ),
                'content' => '<h3>' . __( 'Cara Posting Berita', 'alkautsar' ) . '</h3>' .
                        '<ol>' .
                        '<li>' . __( 'Isi Judul di bagian atas', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Ketik isi berita di kotak editor (mirip Microsoft Word)', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Pilih teks lalu klik <strong>B</strong> (bold), <strong>I</strong> (italic), dll untuk format', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Pilih "Style" di toolbar baris kedua untuk pakai template siap pakai (Pengumuman, Ayat, Banner Donasi)', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Klik <strong>Set featured image</strong> di sidebar kanan untuk upload gambar utama', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Klik tombol <strong>Publish</strong> biru di kanan atas untuk publish', 'alkautsar' ) . '</li>' .
                        '</ol>' .
                        '<h3>' . __( 'Tips', 'alkautsar' ) . '</h3>' .
                        '<ul>' .
                        '<li>' . __( 'Untuk gambar: klik ikon gambar di toolbar', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Untuk link: klik ikon di toolbar', 'alkautsar' ) . '</li>' .
                        '<li>' . __( 'Untuk emoji/simbol: klik ikon di toolbar baris kedua', 'alkautsar' ) . '</li>' .
                        '</ul>',
        ) );
}

/**
 * ════════════════════════════════════════════════════════════════
 * DISABLE COMMENTS — masjid tidak butuh sistem komentar
 * ════════════════════════════════════════════════════════════════
 */

// 1. Disable comments untuk semua post type.
add_filter( 'comments_open', '__return_false', 20, 2 );
add_filter( 'pings_open', '__return_false', 20, 2 );

// 2. Hide existing comments dari front-end.
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

// 3. Remove "Comments" menu dari admin sidebar.
add_action( 'admin_menu', function() {
        remove_menu_page( 'edit-comments.php' );
} );

// 4. Remove comments & pings meta box dari post edit screen.
add_action( 'admin_init', function() {
        remove_meta_box( 'commentsdiv', 'post', 'normal' );
        remove_meta_box( 'commentsdiv', 'page', 'normal' );
        remove_meta_box( 'trackbacksdiv', 'post', 'normal' );
        remove_meta_box( 'trackbacksdiv', 'page', 'normal' );
} );

// 5. Remove "Discussion" from post type supports (tidak ada pilihan enable comments).
//    Tidak pakai filter register_post_type_args karena bisa konflik
//    dengan plugin lain yang set supports sebagai non-array.
//    Pakai remove_post_type_support saja (di bawah) — lebih aman.

// 6. Remove comment support from default Posts & Pages.
add_action( 'init', function() {
        remove_post_type_support( 'post', 'comments' );
        remove_post_type_support( 'post', 'trackbacks' );
        remove_post_type_support( 'page', 'comments' );
        remove_post_type_support( 'page', 'trackbacks' );
}, 100 );

// 7. Remove comment admin bar item.
add_action( 'wp_before_admin_bar_render', function() {
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu( 'comments' );
} );

/**
 * ════════════════════════════════════════════════════════════════
 * CUSTOM TINYMCE BUTTON — "Tambah Kotak Donasi"
 * Tombol shortcut di toolbar untuk insert banner donasi tanpa
 * perlu pilih dari dropdown Styles.
 * ════════════════════════════════════════════════════════════════
 */

// 1. Register TinyMCE plugin (button).
add_action( 'admin_enqueue_scripts', function( $hook ) {
        if ( 'post-new.php' !== $hook && 'post.php' !== $hook ) { return; }
        wp_enqueue_script(
                'alkautsar-mce-plugin',
                ALKAUTSAR_URI . '/assets/js/mce-plugin.js',
                array(),
                ALKAUTSAR_VERSION,
                true
        );
        wp_localize_script( 'alkautsar-mce-plugin', 'alkautsarMceData', array(
                'donasiUrl' => home_url( '/donasi' ),
        ) );
} );

// 2. Tambah tombol ke toolbar baris pertama.
add_filter( 'mce_buttons', function( $buttons ) {
        // Insert tombol "Kotak Donasi" setelah tombol image.
        $insert_at = array_search( 'image', $buttons, true );
        if ( false !== $insert_at ) {
                array_splice( $buttons, $insert_at + 1, 0, 'alkautsar_donasi' );
        } else {
                $buttons[] = 'alkautsar_donasi';
        }
        // Insert tombol "Pengumuman" setelah tombol blockquote.
        $insert_at = array_search( 'blockquote', $buttons, true );
        if ( false !== $insert_at ) {
                array_splice( $buttons, $insert_at + 1, 0, 'alkautsar_pengumuman' );
        }
        return $buttons;
}, 20 );

// 3. Register plugin ke TinyMCE.
add_filter( 'mce_external_plugins', function( $plugins ) {
        $plugins['alkautsar_mce'] = ALKAUTSAR_URI . '/assets/js/mce-plugin.js';
        return $plugins;
} );

/**
 * ════════════════════════════════════════════════════════════════
 * ADMIN MENU — Halaman "Panduan Visual" untuk pengurus
 * ════════════════════════════════════════════════════════════════
 */
add_action( 'admin_menu', function() {
        add_menu_page(
                __( 'Panduan Visual', 'alkautsar' ),
                __( 'Panduan Visual', 'alkautsar' ),
                'edit_posts',
                'alkautsar-guide',
                'alkautsar_render_guide_page',
                'dashicons-book-alt',
                3
        );
} );

function alkautsar_render_guide_page() {
        ?>
        <div class="wrap alkautsar-guide">
                <h1>Panduan Visual Pengelolaan Website Masjid Al-Kautsar</h1>
                <p style="font-size:16px; color:#555; max-width:800px;">
                        Selamat datang! Halaman ini berisi panduan langkah-demi-langkah untuk mengelola website Masjid Al-Kautsar.
                </p>

                <div class="alkautsar-guide-grid">

                        <section class="alkautsar-guide-card">
                                <h2>1. Posting Berita</h2>
                                <ol>
                                        <li>Klik menu <strong>Posts (Berita) &rarr; Tambah Berita</strong>.</li>
                                        <li>Isi <strong>Judul</strong> di bagian atas.</li>
                                        <li>Ketik isi berita di editor (mirip Microsoft Word).</li>
                                        <li>Toolbar atas untuk format: <strong>B</strong> (bold), <strong>I</strong> (italic), <strong>U</strong> (underline), <strong>gambar</strong>, <strong>tautan</strong>.</li>
                                        <li>Di sidebar kanan: <strong>Featured Image &rarr; Set featured image</strong> &rarr; upload gambar.</li>
                                        <li>Klik tombol biru <strong>Publish</strong> di kanan atas.</li>
                                </ol>
                                <p class="tip"><strong>Tip:</strong> Klik tombol <strong>Kotak Donasi</strong> di toolbar untuk otomatis tambah banner donasi di berita.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>2. Tambah Kegiatan Mendatang</h2>
                                <ol>
                                        <li>Klik menu <strong>Kegiatan &rarr; Tambah Kegiatan</strong>.</li>
                                        <li>Isi Judul (contoh: "Kajian Kitab Bulughul Maram").</li>
                                        <li>Ketik deskripsi kegiatan di editor.</li>
                                        <li>Di kotak <strong>Detail Kegiatan</strong> (sidebar kanan), isi: Tanggal, Jam Mulai, Jam Selesai, Lokasi, Pembicara, Kategori.</li>
                                        <li>Publish.</li>
                                </ol>
                                <p class="tip">Kegiatan otomatis muncul di homepage section "Kegiatan Mendatang" dan di halaman <code>/kegiatan</code>.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>3. Tambah Program</h2>
                                <ol>
                                        <li>Klik menu <strong>Program &rarr; Tambah Program</strong>.</li>
                                        <li>Isi Judul dan deskripsi program.</li>
                                        <li>Di <strong>Detail Program</strong> (sidebar kanan): pilih Ikon, isi Jadwal & Lokasi.</li>
                                        <li>Upload Featured Image (foto program).</li>
                                        <li>Publish.</li>
                                </ol>
                                <p class="tip">Program otomatis tampil di homepage section "Program yang Bermanfaat bagi Umat". Tombol "Selengkapnya" akan langsung ke halaman detail program.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>4. Tambah Album Galeri Foto</h2>
                                <ol>
                                        <li>Klik menu <strong>Galeri Foto &rarr; Tambah Album</strong>.</li>
                                        <li>Isi <strong>Judul Album</strong> (contoh: "Kerja Bakti Rusunawa").</li>
                                        <li>Isi <strong>deskripsi kegiatan</strong> di editor (opsional).</li>
                                        <li>Di kotak <strong>Foto Album (Multiple)</strong>: klik tombol <strong>Tambah / Upload Foto</strong>.</li>
                                        <li>Pilih multiple foto sekaligus (bisa dari Media Library atau upload baru).</li>
                                        <li>Drag foto untuk reorder (foto pertama jadi thumbnail album).</li>
                                        <li>Pilih <strong>Kategori</strong> (contoh: Sosial, Kajian, Ramadhan) di sidebar kanan.</li>
                                        <li>Publish.</li>
                                </ol>
                                <p class="tip">Halaman <code>/galeri</code> menampilkan thumbnail semua album. Klik album &rarr; lihat semua foto dengan lightbox (navigasi panah kiri/kanan).</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>5. Tambah Pengurus DKM (dengan Foto)</h2>
                                <ol>
                                        <li>Klik menu <strong>Pengurus DKM &rarr; Tambah Anggota</strong>.</li>
                                        <li>Isi <strong>Nama</strong> lengkap.</li>
                                        <li>Pilih <strong>Jabatan</strong>: Ketua, Wakil, Sekretaris, Bendahara, Imam, Kepala Bidang, Pengurus, atau Lainnya.</li>
                                        <li>Isi <strong>Bio Singkat</strong> (opsional).</li>
                                        <li>Upload <strong>Foto</strong> di Featured Image (sidebar kanan).</li>
                                        <li>Atur <strong>Urutan</strong> via "Order" field (0 = pertama, 1 = kedua, dst).</li>
                                        <li>Publish.</li>
                                </ol>
                                <p class="tip">Pengurus DKM otomatis tampil di halaman <code>/profil</code> dengan foto dan jabatan.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>6. Input Laporan Keuangan</h2>
                                <ol>
                                        <li>Klik menu <strong>Laporan Keuangan &rarr; Tambah Laporan</strong>.</li>
                                        <li>Isi Judul (contoh: "Laporan Bulanan Januari 2026").</li>
                                        <li>Di <strong>Detail Laporan Keuangan</strong>:
                                                <ul>
                                                        <li><strong>Periode:</strong> Harian / Mingguan / Bulanan / Triwulan / Tahunan / Ramadhan / Qurban / Acara</li>
                                                        <li><strong>Tahun:</strong> 2026</li>
                                                        <li><strong>Total Pemasukan:</strong> angka saja (otomatis format Rp)</li>
                                                        <li><strong>Total Pengeluaran:</strong> angka saja</li>
                                                        <li><strong>Rincian Item:</strong> opsional, satu per baris</li>
                                                        <li><strong>File PDF:</strong> upload bukti laporan (opsional)</li>
                                                </ul>
                                        </li>
                                        <li>Publish.</li>
                                </ol>
                                <p class="tip">Untuk ringkasan keuangan tahun berjalan (pemasukan/pengeluaran/saldo) & jumlah penerima manfaat, gunakan menu <strong>Beranda (Transparansi)</strong>.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>7. Edit Beranda (Hero, Tentang, Transparansi)</h2>
                                <p style="margin-top:0;">Tiga menu terpisah untuk mengatur tampilan beranda:</p>
                                <ul>
                                        <li><strong>Beranda (Hero):</strong> ayat Arab, judul utama, subtitle</li>
                                        <li><strong>Beranda (Tentang):</strong> gambar masjid, judul section "Tentang Kami", paragraf, daftar keunggulan, badge (12+ Tahun Mengabdi)</li>
                                        <li><strong>Beranda (Transparansi):</strong> display section transparansi, ringkasan keuangan tahun ini, statistik (3 kotak), jumlah penerima manfaat (5 kategori + total otomatis)</li>
                                </ul>
                                <p class="tip">Klik menu di sidebar &rarr; isi form &rarr; klik <strong>Simpan Perubahan</strong>.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>8. Profil Masjid & Pengaturan Lainnya</h2>
                                <ul>
                                        <li><strong>Profil Masjid:</strong> sejarah, visi, misi</li>
                                        <li><strong>Pengaturan Donasi:</strong> bank, QRIS, WhatsApp konfirmasi</li>
                                        <li><strong>Pengaturan Kontak:</strong> alamat, telepon, email, sosial media, koordinat peta, metode jadwal sholat</li>
                                </ul>
                                <p class="tip">Untuk upload logo masjid: <strong>Appearance &rarr; Customize &rarr; Site Identity</strong>.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>9. Toolbar Editor — Tombol Khusus</h2>
                                <table class="widefat" style="border:none;">
                                        <tbody>
                                                <tr><td><strong>B</strong> / <strong>I</strong> / <strong>U</strong></td><td>Bold / Italic / Underline</td></tr>
                                                <tr><td><strong>Kotak Donasi</strong></td><td>Tambah banner donasi otomatis</td></tr>
                                                <tr><td><strong>Pengumuman</strong></td><td>Tambah kotak pengumuman emas</td></tr>
                                                <tr><td><strong>Styles</strong> (baris 2)</td><td>Template: Ayat, Info Box, Banner, dll</td></tr>
                                                <tr><td><strong>Format</strong> (baris 2)</td><td>Pilih heading level (H1, H2, H3)</td></tr>
                                                <tr><td><strong>A warna</strong> (baris 2)</td><td>Warna teks & background</td></tr>
                                        </tbody>
                                </table>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>10. Role Pengguna & Hak Akses</h2>
                                <p style="margin-top:0;">Website ini punya 3 role khusus (selain Administrator WordPress):</p>
                                <ul>
                                        <li><strong>Humas (Superadmin):</strong> Akses penuh ke semua menu & pengaturan.</li>
                                        <li><strong>Bendahara:</strong> Kelola Laporan Keuangan, Beranda (Transparansi), Pengaturan Donasi.</li>
                                        <li><strong>Editor Masjid:</strong> Kelola Berita, Program, Kegiatan, Pengurus DKM, Galeri Foto, Beranda (Hero & Tentang), Profil Masjid.</li>
                                </ul>
                                <p class="tip">Untuk tambah user baru: <strong>Users &rarr; Add New</strong> &rarr; pilih Role yang sesuai.</p>
                        </section>

                        <section class="alkautsar-guide-card">
                                <h2>11. Backup Website (PENTING!)</h2>
                                <ol>
                                        <li>Install plugin <strong>UpdraftPlus</strong> (gratis di Plugins &rarr; Add New).</li>
                                        <li>Settings &rarr; UpdraftPlus Backups &rarr; tab Settings.</li>
                                        <li>Schedule: Files + Database <strong>Weekly</strong>, Retention 4 weeks.</li>
                                        <li>Pilih cloud: Google Drive atau Dropbox (gratis).</li>
                                        <li>Save Changes.</li>
                                </ol>
                                <p class="tip warning">Backup = asuransi. Kalau ada masalah, website bisa dikembalikan dalam 5 menit.</p>
                        </section>

                </div>

                <style>
                        .alkautsar-guide { max-width: 1200px; }
                        .alkautsar-guide h1 {
                                color: #3B1E12;
                                font-family: "Cormorant Garamond", serif;
                                font-size: 32px;
                                margin-bottom: 16px;
                        }
                        .alkautsar-guide-grid {
                                display: grid;
                                grid-template-columns: repeat(2, 1fr);
                                gap: 20px;
                                margin-top: 24px;
                        }
                        .alkautsar-guide-card {
                                background: #fff;
                                border: 1px solid #e0e0e0;
                                border-radius: 12px;
                                padding: 24px;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                        }
                        .alkautsar-guide-card h2 {
                                color: #3B1E12;
                                font-family: "Cormorant Garamond", serif;
                                font-size: 22px;
                                margin-top: 0;
                                padding-bottom: 12px;
                                border-bottom: 2px solid #D4AF37;
                        }
                        .alkautsar-guide-card ol, .alkautsar-guide-card ul { padding-left: 20px; }
                        .alkautsar-guide-card li { margin-bottom: 6px; line-height: 1.6; }
                        .alkautsar-guide-card code {
                                background: #F1E7D2;
                                color: #B8901E;
                                padding: 2px 6px;
                                border-radius: 4px;
                                font-family: "Courier New", monospace;
                        }
                        .alkautsar-guide-card .tip {
                                background: #F1E7D2;
                                border-left: 4px solid #D4AF37;
                                padding: 10px 14px;
                                border-radius: 4px;
                                margin-top: 16px;
                                font-size: 14px;
                        }
                        .alkautsar-guide-card .tip.warning {
                                background: #fff4e5;
                                border-left-color: #E07B00;
                        }
                        .alkautsar-guide-card table { width: 100%; border-collapse: collapse; }
                        .alkautsar-guide-card td { padding: 8px 12px; border-bottom: 1px solid #f0f0f0; }
                        .alkautsar-guide-card td:first-child { width: 35%; font-weight: 600; }
                        @media (max-width: 768px) {
                                .alkautsar-guide-grid { grid-template-columns: 1fr; }
                        }
                </style>
        </div>
        <?php
}

/**
 * AJAX: cache prayer times server-side (OWASP: never trust remote API directly on each request).
 */
function alkautsar_ajax_prayer_times() {
        check_ajax_referer( 'alkautsar_prayer_nonce', 'nonce' ); // OWASP A07:2021 — CSRF protection.

        $cache_key = 'alkautsar_prayer_' . gmdate( 'Y-m-d' );
        $cached    = get_transient( $cache_key );

        if ( false !== $cached ) {
                wp_send_json_success( $cached );
        }

        $lat    = isset( $_POST['lat'] ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '-6.2088';
        $lng    = isset( $_POST['lng'] ) ? sanitize_text_field( wp_unslash( $_POST['lng'] ) ) : '106.8456';
        $method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '20';

        // Validate numeric — OWASP A03:2021 Injection prevention.
        if ( ! is_numeric( $lat ) || ! is_numeric( $lng ) || ! is_numeric( $method ) ) {
                wp_send_json_error( array( 'message' => 'Invalid coordinates' ) );
        }

        $url = sprintf(
                'https://api.aladhan.com/v1/timings/%s?latitude=%s&longitude=%s&method=%s',
                gmdate( 'd-m-Y' ),
                $lat,
                $lng,
                $method
        );

        $response = wp_remote_get( $url, array( 'timeout' => 10 ) );
        if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => 'Remote API unavailable' ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['data']['timings'] ) ) {
                wp_send_json_error( array( 'message' => 'Malformed response' ) );
        }

        $timings = array_intersect_key(
                $data['data']['timings'],
                array_flip( array( 'Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha' ) )
        );

        set_transient( $cache_key, $timings, DAY_IN_SECONDS );
        wp_send_json_success( $timings );
}
add_action( 'wp_ajax_alkautsar_prayer_times', 'alkautsar_ajax_prayer_times' );
add_action( 'wp_ajax_nopriv_alkautsar_prayer_times', 'alkautsar_ajax_prayer_times' );

/**
 * AJAX: monthly prayer calendar (cached server-side per month).
 */
function alkautsar_ajax_prayer_calendar() {
        check_ajax_referer( 'alkautsar_prayer_nonce', 'nonce' );

        $year   = isset( $_POST['year'] ) ? sanitize_text_field( wp_unslash( $_POST['year'] ) ) : gmdate( 'Y' );
        $month  = isset( $_POST['month'] ) ? sanitize_text_field( wp_unslash( $_POST['month'] ) ) : gmdate( 'n' );
        $lat    = isset( $_POST['lat'] ) ? sanitize_text_field( wp_unslash( $_POST['lat'] ) ) : '-6.2088';
        $lng    = isset( $_POST['lng'] ) ? sanitize_text_field( wp_unslash( $_POST['lng'] ) ) : '106.8456';
        $method = isset( $_POST['method'] ) ? sanitize_text_field( wp_unslash( $_POST['method'] ) ) : '20';

        if ( ! is_numeric( $year ) || ! is_numeric( $month ) || ! is_numeric( $lat ) || ! is_numeric( $lng ) || ! is_numeric( $method ) ) {
                wp_send_json_error( array( 'message' => 'Invalid parameters' ) );
        }

        $cache_key = 'alkautsar_prayer_cal_' . $year . '_' . $month . '_' . $lat . '_' . $lng;
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
                wp_send_json_success( $cached );
        }

        $url = sprintf(
                'https://api.aladhan.com/v1/calendar/%d/%d?latitude=%s&longitude=%s&method=%s',
                (int) $year,
                (int) $month,
                $lat,
                $lng,
                $method
        );

        $response = wp_remote_get( $url, array( 'timeout' => 15 ) );
        if ( is_wp_error( $response ) ) {
                wp_send_json_error( array( 'message' => 'Remote API unavailable' ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['data'] ) || ! is_array( $data['data'] ) ) {
                wp_send_json_error( array( 'message' => 'Malformed response' ) );
        }

        // Simplify payload to reduce size.
        $simplified = array();
        foreach ( $data['data'] as $day ) {
                if ( ! isset( $day['date']['gregorian']['day'] ) || ! isset( $day['timings'] ) ) {
                        continue;
                }
                $simplified[] = array(
                        'day'      => (int) $day['date']['gregorian']['day'],
                        'hijri'    => isset( $day['date']['hijri']['day'] ) ? $day['date']['hijri']['day'] : '',
                        'hijri_month' => isset( $day['date']['hijri']['month']['en'] ) ? $day['date']['hijri']['month']['en'] : '',
                        'timings'  => array_intersect_key(
                                $day['timings'],
                                array_flip( array( 'Fajr', 'Sunrise', 'Dhuhr', 'Asr', 'Maghrib', 'Isha' ) )
                        ),
                );
        }

        set_transient( $cache_key, $simplified, WEEK_IN_SECONDS );
        wp_send_json_success( $simplified );
}
add_action( 'wp_ajax_alkautsar_prayer_calendar', 'alkautsar_ajax_prayer_calendar' );
add_action( 'wp_ajax_nopriv_alkautsar_prayer_calendar', 'alkautsar_ajax_prayer_calendar' );

/**
 * Add defer to non-critical scripts.
 */
function alkautsar_defer_scripts( $tag, $handle ) {
        $defer = array( 'alkautsar-prayer' );
        if ( in_array( $handle, $defer, true ) ) {
                return str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
}
add_filter( 'script_loader_tag', 'alkautsar_defer_scripts', 10, 2 );

/**
 * Preconnect for Google Fonts — performance optimisation.
 */
function alkautsar_resource_hints( $hints, $relation ) {
        if ( 'preconnect' === $relation ) {
                $hints[] = array(
                        'href' => 'https://fonts.googleapis.com',
                );
                $hints[] = array(
                        'href' => 'https://fonts.gstatic.com',
                        'crossorigin',
                );
                $hints[] = array(
                        'href' => 'https://api.aladhan.com',
                );
        }
        return $hints;
}
add_filter( 'wp_resource_hints', 'alkautsar_resource_hints', 10, 2 );
