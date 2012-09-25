<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>
<div id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class(); ?>>

	<div class="bbp-reply-content">

		<?php do_action( 'bbp_theme_before_reply_content' ); ?>

		<?php bbp_reply_content(); ?>

		<?php do_action( 'bbp_theme_after_reply_content' ); ?>

	</div><!-- .bbp-reply-content -->

	<div class="bbp-meta">
		<cite class="author">
			<?php
			if (bbp_is_reply_anonymous(bbp_get_reply_id())) {
				bbp_reply_author_link( array('show_role' => false , 'type' => 'name'));
			} else {
				the_author_posts_link();
			}
			?>
		</cite> &middot;
		<a class="time permalink" href="<?php bbp_reply_url(); ?>" title="<?php bbp_reply_title(); ?>">
			<?php printf( __( '%1$s at %2$s', 'bbpress' ), get_the_date('Y-m-d'), esc_attr( get_the_time() ) ); ?>
			&middot; #<?php bbp_reply_id(); ?>
		</a>

	</div><!-- .bbp-meta -->

</div><!-- #post-<?php bbp_reply_id(); ?> -->
