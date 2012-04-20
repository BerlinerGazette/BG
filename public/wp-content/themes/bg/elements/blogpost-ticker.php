<ul id="blogpost-ticker">
<?php
$arguments = array(
	'offset' => 0,
	'numberposts' => 10,
	'order' => 'DESC',
	'post_type' => 'post',
	'post_status' => 'publish',
);
$latestBlogPosts = get_posts($arguments);
foreach($latestBlogPosts as $latestBlogPost) {
	$label = $title = $latestBlogPost->post_title;
	if (strlen($label) > 70) {
		$label = substr($label, 0, 77).'…';
	}
	$href = get_permalink($latestBlogPost->ID);
	?>
	<li>
		<a href="<?= $href; ?>" title="<?= htmlentities($title, null, 'UTF-8') ?>"><?= htmlentities($label, null, 'UTF-8'); ?></a>
	</li>
	<?php
}
?>
</ul>