<?php

function printMainMenuEntry(Array $entry = array())
{
	global $cat;
	$classes = array();
	if ($entry['category']->category_parent == $cat) {
		$classes[] = 'opened';
	}
	if ($entry['category']->cat_ID == $cat) {
		$classes[] = 'selected';
	};
	if ($entry['children']) {
		// foreach($entry['children'] as $child) {
		// 		if ($child->cat_ID == $cat) {
		// 			$classes[] = 'selected';
		// 			continue;
		// 		}
		// 	}
	}
	?>
	<li<?php if (!empty($classes)) echo ' class="'.implode(' ', array_unique($classes)).'"'; ?>>
		<a href="<?= $entry['url'] ?>" title="<?= $entry['label']; ?>">
			<?= $entry['label']; ?>
		</a>
		<?php if (!empty($entry['children'])) { ?>
		<ul class="subMenu">
			<?php
			foreach($entry['children'] as $childMenuEntry) {
				printMainMenuEntry($childMenuEntry);
			} ?>
		</ul>
		<?php } ?>
	</li>
	<?php
}

/**
 * MainMenu Element
 * 
 * @author Marcel Eichner // Ephigenia <love@ephigenia.de>
 * @since 2010-01-24
 */
?>
<div id="mainMenu">
	<ul>
		<?php
		$categories = get_categories('exclude=1,36&hierarchical=1&pad_counts=0&hide_empty=0&order=ASC&oderby=parent');
		$mainMenu = array();
		foreach($categories as $category) {
			if (!empty($category->category_parent)) continue;
			$linkParams = array(
				'category' => $category,
				'url' => get_category_link($category->term_id),
				'label' => $category->name,
				'children' => array(),
			);
			$children = get_categories('child_of='.$category->cat_ID.'&pad_counts=0&hide_empty=0');
			foreach($children as $subCategory) {
				// only show one level of depth
				if ($subCategory->parent != $category->cat_ID) continue;
				$linkParams['children'][] = array(
					'category' => $subCategory,
					'url' => get_category_link($subCategory->term_id),
					'label' => $subCategory->name,
				);
			}
			$mainMenu[] = $linkParams;
		}
		foreach($mainMenu as $MainMenuEntry) {
			echo printMainMenuEntry($MainMenuEntry);
		}
		?>			
	</ul>
	<?php require_once (dirname(__FILE__).'/searchform.php'); ?>
</div>