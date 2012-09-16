<div class="sidebar leftSidebar">
	<ul>
	<?php
		// get category id when in single post view
		if (is_single()) {
			$category = get_the_category();
			$cat = $category[0]->cat_ID;
		}
		// Liquid Writing Sidebar
		if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$liquidwriting['category_id']) {
			dynamic_sidebar('liquidwriting');
		// Lebenskünstler
		} elseif (
		 	   (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$lebenskuenstler['category_id'])
			|| (is_tag() && in_array($tag, BGProjectConfig::$lebenskuenstler['tags']))
			) {
			dynamic_sidebar('lebenskuenstler-left');
		// L311 / Symposium Learning from Fukushima
		} elseif (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$l311['category_id']) {
			dynamic_sidebar('l311-left');
		// BQV (2012)
		} elseif (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$bqv['category_id']) {
			dynamic_sidebar('bqv-left');
			require TEMPLATEPATH.'/elements/teaser/hund.php';
		// BBPress Sidebar
		} elseif (function_exists('is_bbpress') && is_bbpress()) {
			dynamic_sidebar('sidebar-bbpress-left');
			?>
			<iframe width="260" height="450" class="bbpress-twitter-wall" src="http://twitter-wall.berlinergazette.de/"></iframe>
			<?php
		// Digital Backyards (2012)
		} elseif (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$digitalBackyards['category_id']) {
			dynamic_sidebar('sidebar-digital-backyards-left');
			require TEMPLATEPATH.'/elements/teaser/hund.php';
		// Everything else
		} else {
			dynamic_sidebar('main_sidebar_left_top');
		}
		?>
	</ul>
</div>