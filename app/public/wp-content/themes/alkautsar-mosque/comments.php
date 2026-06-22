<?php
/**
 * Comments template.
 *
 * @package AlKautsar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area" style="max-width: 760px; margin: 3rem auto 0;">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title" style="margin-bottom: 1.5rem;">
			<?php
			$comment_count = get_comments_number();
			printf(
				/* translators: %s: comment count number. */
				esc_html( _n( '%s Komentar', '%s Komentar', $comment_count, 'alkautsar' ) ),
				esc_html( number_format_i18n( $comment_count ) )
			);
			?>
		</h2>

		<ol class="comment-list" style="padding:0; list-style:none; display:grid; gap:1rem;">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 48,
			) );
			?>
		</ol>

		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form( array(
		'title_reply'        => esc_html__( 'Tinggalkan Komentar', 'alkautsar' ),
		'title_reply_to'     => esc_html__( 'Balas ke %s', 'alkautsar' ),
		'cancel_reply_link'  => esc_html__( 'Batal balas', 'alkautsar' ),
		'label_submit'       => esc_html__( 'Kirim Komentar', 'alkautsar' ),
		'class_submit'       => 'btn btn--primary',
	) );
	?>
</div>
