<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center" class="page">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<h1><?php the_title(); ?></h1>	
	<div class="entry">		
		<?php the_content('<p>Lese den Rest dieser Seite &rarr;</p>'); ?>
		<?php link_pages('<p><strong>Seiten:</strong> ', '</p>', 'number'); ?>
	</div>
	<?php if ('open' == $post-> comment_status) { ?>
		<p class="tagged"><a href="<?php the_permalink() ?>#comments"><?php comments_number('Keine Kommentare', '1 Kommentar', '% Kommentare'); ?></a></p>
		<div class="clear"></div>
	<?php } else { ?>
		<div class="clear rule"></div>
	<?php } ?>
			
	<?php endwhile; endif; ?>
		
	<?php if ('open' == $post-> comment_status) { comments_template(); } ?>
	</div>
	
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>