<div class="rightSidebar sidebar">
	<ul>
	<?php
	if (is_tag() && in_array($tag, BGProjectConfig::$lebenskuenstler['tags'])) {
		$cat = get_category(BGProjectConfig::$lebenskuenstler['category_id']);
	}
	
	if (empty($cat) && is_single()) {
		$category = get_the_category();
		$cat = $category[0]->term_id;
	}
	// Lebenskünstler
	if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$lebenskuenstler['category_id']) {
		?>
		<li>
			<a class="teaser" style="color: #3b5998;" href="http://www.facebook.com/home.php?sk=group_151744884866721" title="Lebenskünstler bei Facebook" rel="external">Lebenskünstler bei Facebook</strong></a>
		</li>
		<?php
		if (isset($category)) unset($category);
	}
	
	// CATEGORY PAGE
	if (!isset($cat) || (isset($cat) && !in_array($cat, array(
		BGProjectConfig::$bqv['category_id'],
		BGProjectConfig::$digitalBackyards['category_id'],
		)))) {
		require TEMPLATEPATH.'/elements/categoryPage.php';
	}
	
	// TAG PAGE
	require TEMPLATEPATH.'/elements/tagPage.php';
	
	// RECENT COMMENTS
	if (is_home()) {
		require TEMPLATEPATH.'/elements/recentComments.php';
	}
	
	// GLOBAL RIGHT DETAILPAGE SIDEBAR
	$showMainSidebarDetailPage = true;
	if (is_single()) {
		$showMainSidebarDetailPage = true;
	}
	if (isset($cat) && in_array($cat, array(BGProjectConfig::$bqv['category_id']))) {
		$showMainSidebarDetailPage = false;
	}
	if ($showMainSidebarDetailPage) {
		dynamic_sidebar('main_sidebar_right_detailpage');
	}

	// recent blog posts
	// if (is_single()) {
	// 	require TEMPLATEPATH.'/elements/recentPosts.php';
	// }
	if (!isset($cat) || (isset($cat) && !in_array($cat, array(
		BGProjectConfig::$liquidwriting['category_id'],
		BGProjectConfig::$lebenskuenstler['category_id'],
		BGProjectConfig::$l311['category_id'],
		BGProjectConfig::$bqv['category_id'],
		BGProjectConfig::$digitalBackyards['category_id'],
		)))) {
		require TEMPLATEPATH.'/elements/feeds.php';
		require TEMPLATEPATH.'/elements/teaser/hund.php';
		require TEMPLATEPATH.'/elements/newsletterForm.php';	
	}	
	
	// show sidebar only in author, index page, search
	if (is_home() || (is_tag() && empty($cat))) {
		if (!empty($cat) && $cat &&
			(
				cat_is_ancestor_of($cat, 37) || $cat == 37
			||	cat_is_ancestor_of($cat, 43) || $cat == 43
			)
			) {
			require TEMPLATEPATH.'/elements/flickr_badge.php';
		}
		if (function_exists('dynamic_sidebar')) {
			dynamic_sidebar('main_sidebar_right');
		}
	}
	
	// get category id when in single post view
	if (is_single()) {
		$category = get_the_category();
		$cat = $category[0]->cat_ID;
	}
	if (function_exists('dynamic_sidebar')) {
		// Lebenskünstler
		if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$lebenskuenstler['category_id']) {
			dynamic_sidebar('lebenskuenstler-right');
		}
		// L311 / Symposium Learning from Fukushima Fukushima
		if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$l311['category_id']) {
			dynamic_sidebar('l311-right');
		}
		// BQV (2012)
		if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$bqv['category_id']) {
			dynamic_sidebar('bqv-right');
		}
		// digital backyards
		if (isset($cat) && !is_front_page() && $cat == BGProjectConfig::$digitalBackyards['category_id']) {
			dynamic_sidebar('sidebar-digital-backyards-right');
		}
	}
	
	?>
	</ul>	
</div>