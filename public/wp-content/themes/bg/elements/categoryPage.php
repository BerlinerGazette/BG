<?php

if (!empty($post) && $category = get_the_category()) {
	if (is_author()) {
		$pageNames[] = 'AutorIn werden!';
		$pageNames[] = $author->first_name.' '.$author->last_name;
	} else {	
		$category = $category[0];
		$parentCategories = get_category_parents($cat);
		if (is_string($parentCategories)) {
			$pageNames = array_filter(split('/', $parentCategories));
			$pageNames = array_reverse($pageNames);
		}
	}
} elseif (is_category()) {
	$category = get_category($cat);
	$pageNames[] = $category->name;
}
if (is_search()) {
	$pageNames[] = 'Suche';
}

if (!empty($pageNames)) foreach($pageNames as $pagename) {
	if (!($page = get_page_by_title($pagename.'_Sidebar', false))) continue;
	// echo first page of a category found and quit
	?>
	<li class="teaser">
		<h2><?= substr($page->post_title, 0, -8); ?></h2>
		<?= nl2br($page->post_content); ?>
	</li>
	<?php
	break;
}

