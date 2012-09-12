<?php get_header(); ?>
<div id="center" class="bbpress" role="main">
	<?php do_action( 'bbp_template_notices' ); ?>
	<?php the_content(); ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>