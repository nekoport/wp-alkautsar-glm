<?php
/**
 * Single post template.
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
		<?php
		$categories = get_the_category();
		if ( $categories ) {
			echo '<div class="breadcrumb">';
			echo '<a href="' . esc_url( home_url( '/berita' ) ) . '">' . esc_html__( 'Berita', 'alkautsar' ) . '</a>';
			echo ' › ';
			echo esc_html( $categories[0]->name );
			echo '</div>';
		}
		?>
	</div>
</header>

<main id="primary" class="site-main">
	<div class="container single-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
				<header class="entry-header" style="text-align:center; margin-bottom:2rem;">
					<?php alkautsar_entry_categories(); ?>
					<h1 class="entry-title" style="margin:0.5rem 0 1rem; max-width: 800px; margin-left:auto; margin-right:auto;"><?php the_title(); ?></h1>
					<div class="entry-meta" style="justify-content:center; border:0;">
						<?php alkautsar_posted_on(); ?>
						<span>·</span>
						<?php alkautsar_posted_by(); ?>
					</div>
				</header>

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

				<footer class="entry-footer" style="max-width:760px; margin: 2rem auto 0; padding-top: 1.5rem; border-top: 1px solid var(--line); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
					<?php
					$tags = get_the_tag_list( '', ', ' );
					if ( $tags ) {
						echo '<div><strong>' . esc_html__( 'Tag:', 'alkautsar' ) . '</strong> ' . wp_kses_post( $tags ) . '</div>';
					}
					?>
					<a href="<?php echo esc_url( home_url( '/berita' ) ); ?>">← <?php esc_html_e( 'Kembali ke Berita', 'alkautsar' ); ?></a>
				</footer>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		<?php endwhile; ?>
	</div>
</main>

<?php
get_footer();
