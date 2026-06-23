<?php
/**
 * Pattern: Kutipan Ayat
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '🕌 Kutipan Ayat / Hadis', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Tampilkan ayat Al-Qur\'an atau hadis dengan teks Arab, terjemahan, dan sumber.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-ayat","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-ayat"><!-- wp:paragraph {"className":"akp-ayat-arabic"} -->
<p class="akp-ayat-arabic">إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ وَالْيَوْمِ الْآخِرِ</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-ayat-translation"} -->
<p class="akp-ayat-translation">"Hanyalah yang memakmurkan masjid-masjid Allah ialah orang-orang yang beriman kepada Allah dan hari kemudian."</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"akp-ayat-source"} -->
<p class="akp-ayat-source">— QS. At-Taubah: 18</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->',
);
