<?php
// Forum 
if (function_exists('is_bbpress') && is_bbpress()) {
	$postTypes = array(
		'topic',
		'reply'
	);
	$searchQueryPlaceholder = 'Enter keyword';
	$searchSubmitLabel = 'Search';
} else {
	$searchQueryPlaceholder = 'Suchbegriff eingeben';
	$searchSubmitLabel = 'Finden';
}
?>
<form method="get" id="searchForm" action="<?php bloginfo('home'); ?>/">
	<fieldset>
		<?php if (!empty($postTypes)) foreach($postTypes as $postType) { ?>
		<input type="hidden" name="post_type[]" value="<?= $postType ?>" />
		<?php } ?>
		<input type="text" class="search_input input" value="<?= htmlentities($searchQueryPlaceholder) ?>" name="s" size="21" />
		<input type="submit" class="search_submit submit" id="searchsubmit" value="<?= htmlentities($searchSubmitLabel) ?>" />
	</fieldset>
</form>
