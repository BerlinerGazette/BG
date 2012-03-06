<?php
/**
 * Author Detailpage / Profile Page
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-01-24
 */

if(get_query_var('author')) {
	$author = get_userdata(get_query_var('author'));
} else {
	$author = get_userdatabylogin(get_query_var('author_name'));
}
get_header();
get_sidebar();
?>
<div id="center" class="profile">
	<h1><?= $author->first_name ?> <?= $author->last_name ?></h1>
	<div class="avatar" style="margin-bottom: 1em;">
		<?php if (function_exists('userphoto')) echo userphoto($author); ?>
	</div>
	<p>
		<?= nl2br($author->description); ?>
	</p>
	<?php if (!empty($author->user_url) && $author->user_url !== 'http://') {?>
		<a href="<?= $author->user_url; ?>" title="<?= $author->first_name; ?> Website"><?= $author->user_url; ?></a><br />
	<?php } ?><br />
	
	<?php
	query_posts('author='.$author->ID.'&post_type=post&post_status=publish&posts_per_page=1000');
	if (have_posts()) { ?>
	<h2>Beiträge in der Berliner Gazette</h2>
	<ul class="posts list">
		<?php while (have_posts()) {
			the_post();
			?>
			<li id="post<?php the_ID(); ?>" class="post">
				<time date="<?= the_time('c') ?>"><?php the_time('d.m.Y H:i') ?></time><br />
				<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link zu <?php the_title(); ?>"><?php the_title(); ?></a>
			</li>
		<?php } ?>
	</ul>
	<?php } ?>
	
</div>
<?php require TEMPLATEPATH.'/sidebar2.php'; ?>
<?php get_footer(); ?>