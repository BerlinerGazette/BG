<?php get_header(); ?>
<?php get_sidebar(); ?>
<div id="center" class="bbpress" role="main">
	<?php do_action( 'bbp_template_notices' ); ?>
	<?php the_content(); ?>
</div>
<?php get_footer(); ?>