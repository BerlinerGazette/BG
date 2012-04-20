<div id="footer-navigation">
	<ul>
	<?php
	$arguments = array(
		'exclude' => '1',
		'hierarchical' => true,
		'pad_counts' => false,
		'hide_empty' => false,
		'order' => 'ASC',
		'oderby' => 'parent',
		'parent' => 0,
	);
	$categories = get_categories($arguments);
	foreach($categories as $category) {
		$title =  sprintf('Alle Artikel aus der Kategorie %s anschauen', htmlentities($category->name, null, 'UTF-8'));
		$href = get_category_link($category->term_id);
		?>
		<li>
			<a href="<?= $href ?>" title="<?= $title ?>"><?= $category->name; ?></a>
		</li>
		<?php
	}
	?>
	</ul>
</div>