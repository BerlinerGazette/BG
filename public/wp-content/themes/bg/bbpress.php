<?php get_header(); ?>
<?php require TEMPLATEPATH.'/sidebar.php'; ?>
<div id="center" class="bbpress" role="main">
	<?php do_action( 'bbp_template_notices' ); ?>
	<?php the_content(); ?>
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>