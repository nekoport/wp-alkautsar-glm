<?php
/**
 * Search form.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="search-field" class="screen-reader-text"><?php esc_html_e( 'Cari:', 'alkautsar' ); ?></label>
	<div class="search-form__inner" style="display:flex; gap:0.5rem; max-width:480px; margin: 0 auto;">
		<input type="search" id="search-field" class="search-field" placeholder="<?php esc_attr_e( 'Ketik dan tekan enter…', 'alkautsar' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" style="flex:1; padding: 0.75rem 1rem; border: 1px solid var(--line); border-radius: var(--radius-pill); font-family: inherit;">
		<button type="submit" class="btn btn--primary"><?php esc_html_e( 'Cari', 'alkautsar' ); ?></button>
	</div>
</form>
