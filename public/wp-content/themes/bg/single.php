<?php
get_header();
get_sidebar();
?>
<div id="center">
	<?php if (!have_posts()) { ?>
		<p class="error">
			<?= __('Post nicht gefunden'); ?>
		</p>
	<?php } else {
		while (have_posts()) { ?>
			<ul class="posts">
				<?php
				require TEMPLATEPATH.'/elements/post.php';
				?>
			</ul>
			<br class="c" />
			<?php comments_template();
		}
	} ?>
</div>
<?php
require TEMPLATEPATH.'/sidebar2.php';
get_footer();