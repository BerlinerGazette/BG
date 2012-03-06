<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="CommentForm" accept-charset="UTF-8">	
	<h3 id="respond" class="comments_headers"><?= __('Kommentar hinterlassen')?></h3>
	<fieldset>
		<?php if ( $user_ID ) { ?>
			<p class="unstyled">Eingeloggt als <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e('Logout aus diesem Account') ?>">Logout &raquo;</a></p>	
		<?php } ?>
		<?php if ( !$user_ID ) { ?>
			<p>
				<label for="author"><?= __('Name'); ?></label>
				<input class="text_input" type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" />
			</p>
			<p>
				<label for="email"><?= __('Email'); ?></label>
				<input class="text_input" type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" />
			</p>
			<p>
				<label for="url"><?= __('Website'); ?></label>
				<input class="text_input" type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" />
			</p>
		<?php } ?>
		<p>
			<textarea class="text_input text_area" name="comment" id="comment" rows="7" cols="57" tabindex="4"></textarea>
		</p>
		<?php if (function_exists('show_subscription_checkbox')) { show_subscription_checkbox(); } ?>
		<p>
			<input name="submit" class="form_submit submit" type="submit" id="submit" src="<?php bloginfo('template_url') ?>/images/submit_comment.gif" tabindex="5" value="<?= __('Kommentar absenden'); ?>" />
			<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		</p>
		<?php do_action('comment_form', $post->ID); ?>
	</fieldset>
</form>