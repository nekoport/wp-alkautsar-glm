<?php
/**
 * Pattern: Galeri Kegiatan
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '🖼️ Galeri Kegiatan', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Grid 3 kolom untuk dokumentasi foto kegiatan. Ganti gambar dengan foto Anda.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-gallery","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-gallery"><!-- wp:group {"className":"akp-gallery-item"} -->
<div class="wp-block-group akp-gallery-item"><!-- wp:image -->
<figure class="wp-block-image"><img alt="Kegiatan 1"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"akp-gallery-caption"} -->
<p class="akp-gallery-caption">Kajian Rutin Pekanan</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-gallery-item"} -->
<div class="wp-block-group akp-gallery-item"><!-- wp:image -->
<figure class="wp-block-image"><img alt="Kegiatan 2"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"akp-gallery-caption"} -->
<p class="akp-gallery-caption">Santunan Dhuafa</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-gallery-item"} -->
<div class="wp-block-group akp-gallery-item"><!-- wp:image -->
<figure class="wp-block-image"><img alt="Kegiatan 3"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"akp-gallery-caption"} -->
<p class="akp-gallery-caption">Buka Puasa Bersama</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
