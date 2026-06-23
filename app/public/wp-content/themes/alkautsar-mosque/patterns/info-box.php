<?php
/**
 * Pattern: Info Box
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( 'ℹ️ Info Box', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Kotak info dengan border emas di kiri. Cocok untuk catatan, tips, atau informasi tambahan.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-info-box","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-info-box"><!-- wp:paragraph -->
<p><strong>ℹ️ Catatan:</strong> Tuliskan informasi tambahan di sini. Contoh: "Jadwal dapat berubah sewaktu-waktu. Pantau pengumuman terbaru melalui WhatsApp grup jamaah."</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
);
