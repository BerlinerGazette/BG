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
	
	if (!isset($cat) || (isset($cat) && !in_array($cat, array(
		BGProjectConfig::$bqv['category_id']
		)))) {
		require TEMPLATEPATH.'/elements/categoryPage.php';
	}
	require TEMPLATEPATH.'/elements/tagPage.php';
	
	if (is_category() || is_home() || is_archive()) {
		$showRecentComments = true;
	}
	$oikonomiaCategoryId = 957;
	$europakriseCategoryId = 899;
	$zeitung20CategoryId = 549;
	$bildDerWocheCategoryId = 961;
	$recentCommentsIgnoreCategoryIds = array(
		$oikonomiaCategoryId, $europakriseCategoryId, $zeitung20CategoryId, $bildDerWocheCategoryId,
		$pornoRamaCategoryId = 905,
		$terrorVonRechtsCategoryId = 894,
		$wikiLeaksCategoryId = 343,
	);
	if (isset($cat) && in_array($cat, $recentCommentsIgnoreCategoryIds)) {
		$showRecentComments = false;
	}
	if ($showRecentComments) {
		require TEMPLATEPATH.'/elements/recentComments.php';
	}
	
	if (is_single()) {
		if (function_exists('dynamic_sidebar')) {
			dynamic_sidebar('main_sidebar_right_detailpage');
		}
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
		// require TEMPLATEPATH.'/elements/teaser/emergeandsee.php';
		// require TEMPLATEPATH.'/elements/teaser/polar.php';
		// require TEMPLATEPATH.'/elements/teaser/lima.php';
		// require TEMPLATEPATH.'/elements/teaser/fluter.php';
		// require TEMPLATEPATH.'/elements/teaser/springerin.php';
		// require TEMPLATEPATH.'/elements/teaser/re_campaign.php';
		
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
	}
	
	?>
	
	</ul>	
</div>