<?php
/**
 * Pattern: Pengumuman Penting
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '📢 Pengumuman Penting', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Kotak kuning emas untuk pengumuman penting. Ganti teks sesuai kebutuhan.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-pengumuman","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-pengumuman"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">📢 Pengumuman Penting</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tuliskan isi pengumuman di sini. Contoh: "Diberitahukan kepada seluruh jamaah, bahwa mulai tanggal 1 Ramadhan 1446 H, Tarawih berjamaah akan dimulai pukul 19.30 WIB setelah Isya."</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
);
