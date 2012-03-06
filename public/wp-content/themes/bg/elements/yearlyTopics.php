<?php

// don’t show on lebenskuenstler and learning from fukushima
if (isset($cat)) {
	if ($cat == BGProjectConfig::$lebenskuenstler['category_id']) return false;
	if (is_tag() && in_array($tag, BGProjectConfig::$lebenskuenstler['tags'])) return false;
	if ($cat == BGProjectConfig::$l311['category_id']) return;
}

$topics = get_categories(array(
	'child_of' => 41,
	'pad_counts' => 0,
	'hide_empty' => 0
));

?>
<li class="teaser white">
	<h2>Jahresthemen</h2>
	<form action="#" method="post" accept-charset="UTF-8">
		<fieldset>
			<select name="cat" size="1" class="autoLink">
				<option value=""><?= __('Auswählen …'); ?></option>
				<?php foreach($topics as $subCategory) {
				$selected = ($subCategory->cat_ID == $category->cat_ID) || ($subCategory->cat_ID == $cat);
				?>
				<option value="<?= get_category_link($subCategory->term_id) ?>"<?php
					if ($selected) echo ' class="selected" selected="selected"';
					?>>
					<?= $subCategory->name; ?>
				</option>
				<?php } ?>
			</select>
		</fieldset>
	</form>
</li>