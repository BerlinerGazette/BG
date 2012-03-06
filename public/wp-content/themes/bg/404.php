<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center">
	<?php
	// try to find a page named '404' and print the text of it, or just print
	// a default text
	if ($page = get_page_by_title('404', false)) {
		$title = $page->post_title;
		$text = $page->post_content;
	} else {
		$title = '404';
		$text = 'Page not found';
	}
	?>
	<h2><?= $title; ?></h2>
	<?= $text; ?>
</div>
<?php include TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>