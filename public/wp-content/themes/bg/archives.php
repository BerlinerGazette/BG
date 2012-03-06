<?php
/**
Template Name: Archives
*/
?>
<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center">
	<h2><?= __('Artikel-Archiv'); ?></h2>
	<ul>
		<?php wp_get_archives('type=monthly'); ?>
	</ul>
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>