<?php
/**
 * Pattern: Banner Donasi
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '💛 Banner Donasi', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Kotak gradient coklat dengan tombol menuju halaman donasi. Sangat efektif untuk mendorong donatur.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-banner-donasi","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-banner-donasi"><!-- wp:heading {"level":3,"textAlign":"center"} -->
<h3 class="wp-block-heading has-text-align-center">💛 Salurkan Donasi Terbaik Anda</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Setiap rupiah yang Anda salurkan menjadi sebab turunnya berkah dan terpeliharanya rumah Allah. Donasi dapat melalui transfer bank atau QRIS.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/donasi">Donasi Sekarang</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
);
