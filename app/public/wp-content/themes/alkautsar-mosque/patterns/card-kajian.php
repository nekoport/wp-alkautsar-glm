<?php
/**
 * Pattern: Card Kajian / Profil Ustadz
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '👤 Profil Ustadz / Card Kajian', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Card profil pembicara kajian dengan avatar inisial, nama, peran, dan detail.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-card-kajian","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-card-kajian"><!-- wp:group {"className":"akp-card-kajian-avatar"} -->
<div class="wp-block-group akp-card-kajian-avatar"><strong>A</strong></div>
<!-- /wp:group -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"akp-card-kajian-name"} -->
<p class="akp-card-kajian-name">Ustadz Ahmad Fauzi, Lc.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-card-kajian-role"} -->
<p class="akp-card-kajian-role">Pembicara Kajian</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-card-kajian-detail"} -->
<p class="akp-card-kajian-detail">Lulusan Universitas Al-Azhar Kairo. Mengajar kitab tafsir, fiqih, dan akidah. Kajian rutin setiap ba\'da Maghrib di Aula Masjid.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
