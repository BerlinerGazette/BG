<?php

if (is_tag()) {
	// get the Tag’s full name
	$TagObj = get_term_by('slug', $tag, 'post_tag');
	if ($TagObj) {
		$pageNamePrefix = $TagObj->name;
	} else {
		$pageNamePrefix = $tag;
	}
	$possiblePageName = array(
		$pageNamePrefix.'_TagSidebar',
		$pageNamePrefix.'_Sidebar',
	);
	foreach($possiblePageName as $pageName) {
		if (!($page = get_page_by_title($pageName, false))) continue;
		?>
		<li class="teaser">
			<h2><?= preg_replace('@_(Sidebar|TagSidebar)$@', '', $page->post_title); ?></h2>
			<?= nl2br($page->post_content); ?>
		</li>
		<?php
		break;
	}
}