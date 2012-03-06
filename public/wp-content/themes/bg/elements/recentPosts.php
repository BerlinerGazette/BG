<?php
if (!empty($post) && $cat = get_the_category()) {
	$category = $cat[0];
} else {
	$category = get_category($cat);
}

if (!empty($category)) {
	$headline = sprintf(__('Andere Beiträge zu %s'), $category->name);
} else {
	$headline = __('Andere Beiträge');
}

// other posts from the same category
query_posts('showposts=10&cat='.$category->cat_ID);
if (have_posts()) { ?>
<li class="teaser">
	<h2><?= $headline ?></h2>
	<ul class="posts">
	<?php
	while (have_posts()) {
		the_post(); ?>
		<li id="post<?php the_ID(); ?>" class="post">
			<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a>
		</li>
		<?php
	} // while ?>
	</ul>
</li>
<?php
}