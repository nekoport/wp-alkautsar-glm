<?php
/**
 * Pattern: Highlight Statistik
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '📊 Highlight Statistik', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Tiga kolom angka statistik besar. Cocok untuk menampilkan jumlah jamaah, donasi, atau program.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-stats","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-stats"><!-- wp:group {"className":"akp-stat-item"} -->
<div class="wp-block-group akp-stat-item"><!-- wp:paragraph {"className":"akp-stat-number"} -->
<p class="akp-stat-number">250</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-stat-label"} -->
<p class="akp-stat-label">Jamaah Aktif</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-stat-item"} -->
<div class="wp-block-group akp-stat-item"><!-- wp:paragraph {"className":"akp-stat-number"} -->
<p class="akp-stat-number">37</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-stat-label"} -->
<p class="akp-stat-label">Program Terealisasi</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-stat-item"} -->
<div class="wp-block-group akp-stat-item"><!-- wp:paragraph {"className":"akp-stat-number"} -->
<p class="akp-stat-number">52</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-stat-label"} -->
<p class="akp-stat-label">Kajian Setahun</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
