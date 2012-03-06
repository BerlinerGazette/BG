<?php if (($comments) or ('open' == $post->comment_status)) { ?>
<div id="comments">
	<h3><?php comments_number('Noch keine Kommentare', '1 Kommentar', '% Kommentare' );?> zu <q><?php the_title(); ?></q></h3>
	<?php if ($comments) { ?>
		<ul class="comments">
			<?php foreach ($comments as $comment) { 
				require TEMPLATEPATH.'/elements/comment.php';
			} ?>
		</ul>
	<?php } elseif ('open' == $post->comment_status) { ?> 
		<p class="hint">
			<?= __('Bisher wurden noch keine Kommentare abgegeben.'); ?>
		</p>
	<?php } else if (is_single) { ?>
		<p class="hint">
			<?= __('Kommentare wurden deaktiviert'); ?>
		</p>
	<?php } ?>
	<?php
	// Comment Form or logged in user name
	if ($post->comment_status == 'open') {
		if (get_option('comment_registration') && !$user_ID ) { ?>
			<p class="hint">
				Du musst <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">eingeloggt</a>
				sein um ein Kommentar zu schreiben.
			</p>
		<?php } else {
			require TEMPLATEPATH.'/elements/commentsForm.php';
		} 
	} ?>
</div>
<?php } ?>