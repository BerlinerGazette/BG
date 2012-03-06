<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center">
	<?php
	if (!have_posts()) {
		echo 'Not Found';
	} else {
		?>
		<ul class="posts">
			<?php
			while (have_posts()) {
				require TEMPLATEPATH.'/elements/post.php';
			} ?>
		</ul>
		<?php require TEMPLATEPATH.'/elements/navigation.php';
	} ?>
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>