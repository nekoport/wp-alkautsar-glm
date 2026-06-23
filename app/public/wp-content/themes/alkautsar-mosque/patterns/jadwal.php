<?php
/**
 * Pattern: Tabel Jadwal Kajian Mingguan
 *
 * @package AlKautsar
 */

return array(
	'title'      => __( '📅 Jadwal Kajian Mingguan', 'alkautsar' ),
	'categories' => array( 'alkautsar' ),
	'description' => __( 'Tabel jadwal kajian pekanan dengan kolom Hari, Tema, Pembicara, Waktu.', 'alkautsar' ),
	'content'    => '<!-- wp:group {"className":"akp-jadwal","layout":{"type":"constrained"}} -->
<div class="wp-block-group akp-jadwal"><!-- wp:group {"className":"akp-jadwal-row akp-jadwal-row--header"} -->
<div class="wp-block-group akp-jadwal-row akp-jadwal-row--header"><!-- wp:paragraph {"className":"akp-jadwal-day"} -->
<p class="akp-jadwal-day">Hari</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Tema</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Pembicara</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Waktu</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-jadwal-row"} -->
<div class="wp-block-group akp-jadwal-row"><!-- wp:paragraph {"className":"akp-jadwal-day"} -->
<p class="akp-jadwal-day">Senin</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Tafsir Al-Qur\'an</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ust. Ahmad Fauzi</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ba\'da Maghrib</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-jadwal-row"} -->
<div class="wp-block-group akp-jadwal-row"><!-- wp:paragraph {"className":"akp-jadwal-day"} -->
<p class="akp-jadwal-day">Rabu</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Fiqih Ibadah</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ust. M. Ilham</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Ba\'da Maghrib</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"akp-jadwal-row"} -->
<div class="wp-block-group akp-jadwal-row"><!-- wp:paragraph {"className":"akp-jadwal-day"} -->
<p class="akp-jadwal-day">Jumat</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Khotbah Jumat</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>KH. Abdullah</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>11:30 WIB</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->',
);
