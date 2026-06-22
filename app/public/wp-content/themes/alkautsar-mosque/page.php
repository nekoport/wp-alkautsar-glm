<?php
/**
 * Page template.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<header class="page-header">
	<div class="container">
		<h1><?php the_title(); ?></h1>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container page-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-thumbnail" style="margin: 0 auto 2rem; max-width: 1000px; border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
						<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); ?>
					</figure>
				<?php endif; ?>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'alkautsar' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
